<?php

namespace App\Modules\Language\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Language Override Model
 * 
 * Joomla-style language override system
 * Allows admins to override translations without editing files
 */
class LanguageOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'domain',
        'key',
        'value',
        'original_value',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope: Active overrides only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Filter by locale
     */
    public function scopeForLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Scope: Filter by domain
     */
    public function scopeForDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    /**
     * Creator relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updater relationship
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all active overrides for locale
     * 
     * @param string $locale
     * @param string|null $domain
     * @return array Key-value pairs
     */
    public static function getOverrides(string $locale, ?string $domain = null): array
    {
        $query = static::active()->forLocale($locale);

        if ($domain) {
            $query->forDomain($domain);
        }

        return $query->pluck('value', 'key')->toArray();
    }

    /**
     * Set override value
     * 
     * @param string $locale
     * @param string $key
     * @param string $value
     * @param string $domain
     * @param string|null $originalValue
     * @return static
     */
    public static function setOverride(
        string $locale,
        string $key,
        string $value,
        string $domain = 'default',
        ?string $originalValue = null
    ): self {
        return static::updateOrCreate(
            [
                'locale' => $locale,
                'domain' => $domain,
                'key' => $key,
            ],
            [
                'value' => $value,
                'original_value' => $originalValue ?? __($key),
                'status' => 'active',
                'updated_by' => auth()->id(),
                'created_by' => auth()->id() ?? null,
            ]
        );
    }

    /**
     * Remove override
     * 
     * @param string $locale
     * @param string $key
     * @param string $domain
     * @return bool
     */
    public static function removeOverride(string $locale, string $key, string $domain = 'default'): bool
    {
        return static::where('locale', $locale)
            ->where('domain', $domain)
            ->where('key', $key)
            ->delete();
    }

    /**
     * Clear all overrides cache
     * 
     * @return void
     */
    public static function clearCache(): void
    {
        \Illuminate\Support\Facades\Cache::tags(['language-overrides'])->flush();
    }
}
