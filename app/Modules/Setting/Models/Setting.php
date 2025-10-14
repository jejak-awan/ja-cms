<?php

namespace App\Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\SettingFactory::new();
    }
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // ========================================
    // SCOPES
    // ========================================

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // ========================================
    // STATIC HELPER METHODS
    // ========================================

    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    public static function set(string $key, $value, ?string $group = null, string $type = 'text'): bool
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) || is_object($value) ? json_encode($value) : $value,
                'group' => $group ?? 'general',
                'type' => $type,
            ]
        );

        Cache::forget("setting.{$key}");
        Cache::forget("settings.group.{$group}");
        Cache::forget('settings.all');

        return (bool) $setting;
    }

    public static function forget(string $key): bool
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            Cache::forget("setting.{$key}");
            Cache::forget("settings.group.{$setting->group}");
            Cache::forget('settings.all');
            return (bool) $setting->delete();
        }

        return false;
    }

    public static function getGroup(string $group): array
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            $settings = self::byGroup($group)->get();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    public static function getAll(): array
    {
        return Cache::remember('settings.all', 3600, function () {
            $settings = self::all();
            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }

            return $result;
        });
    }

    public static function getAllGrouped(): array
    {
        $settings = self::orderBy('group')->orderBy('key')->get();
        $grouped = [];

        foreach ($settings as $setting) {
            $group = $setting->group ?? 'general';
            if (!isset($grouped[$group])) {
                $grouped[$group] = [];
            }
            $grouped[$group][$setting->key] = self::castValue($setting->value, $setting->type);
        }

        return $grouped;
    }

    public static function getGroups(): array
    {
        return self::whereNotNull('group')
            ->distinct()
            ->pluck('group')
            ->toArray();
    }

    public static function clearCache(): void
    {
        Cache::flush();
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'number' => (int) $value,
            'float', 'decimal' => (float) $value,
            'json', 'array' => is_string($value) ? (json_decode($value, true) ?? []) : $value,
            default => $value,
        };
    }

    public function getValueAttribute($value)
    {
        return self::castValue($value, $this->type);
    }
}
