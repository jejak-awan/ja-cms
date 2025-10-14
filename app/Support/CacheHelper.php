<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Closure;

class CacheHelper
{
    public static function remember(string $key, string $module, int $ttl = null, Closure $callback)
    {
        if (!Config::get('cache_custom.enabled', true)) {
            // Caching disabled: execute and return without storing
            return $callback();
        }
        $effectiveTtl = $ttl ?? (int) (Config::get("cache_custom.modules.{$module}.ttl") ?? Config::get('cache_custom.ttl', 3600));
        return Cache::remember($key, $effectiveTtl, $callback);
    }

    public static function forget(string $key): void
    {
        Cache::forget($key);
    }
}
