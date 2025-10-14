<?php

namespace App\Modules\Language\Models;

use Database\Factories\LanguageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'flag',
        'direction',
        'is_active',
        'is_default',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get all active languages ordered by order column.
     */
    public static function active()
    {
        return static::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get the default language.
     */
    public static function default()
    {
        return static::where('is_default', true)->first();
    }

    /**
     * Get language by code.
     */
    public static function byCode(string $code)
    {
        return static::where('code', $code)->first();
    }

    /**
     * Scope: Active languages only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): LanguageFactory
    {
        return LanguageFactory::new();
    }
}
