<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Modules\Setting\Models\Setting;

class CacheCustomServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind cache custom config to the container
        $this->app->singleton('cache.custom', function () {
            return Config::get('cache_custom');
        });

        // Remember original cache store to allow toggling back from 'null'
        if (!Config::has('cache_custom._original_store')) {
            Config::set('cache_custom._original_store', Config::get('cache.default'));
        }
    }

    public function boot()
    {
        // Load overrides from settings table when available
        try {
            if (Schema::hasTable('settings')) {
                $enabled = Setting::get('cache.enabled');
                $ttl = Setting::get('cache.ttl');
                $debug = Setting::get('cache.debug');

                if (!is_null($enabled)) {
                    Config::set('cache_custom.enabled', (bool) $enabled);
                }
                if (!is_null($ttl)) {
                    Config::set('cache_custom.ttl', (int) $ttl);
                }
                if (!is_null($debug)) {
                    Config::set('cache_custom.debug', (bool) $debug);
                }
            }
        } catch (\Throwable $e) {
            Log::debug('CacheCustomServiceProvider boot settings override skipped: '.$e->getMessage());
        }

        // Respect enabled toggle by switching to null driver when disabled
        $enabled = (bool) Config::get('cache_custom.enabled', true);
        if (!$enabled) {
            Config::set('cache.default', 'null');
        } else {
            $original = Config::get('cache_custom._original_store', env('CACHE_STORE', 'database'));
            Config::set('cache.default', $original);
        }
    }

    /**
     * Helper to flush all cache (for admin panel)
     */
    public static function flushAll()
    {
        Cache::flush();
    }

    /**
     * Helper to get cache status
     */
    public static function getStatus()
    {
        return [
            'enabled' => Config::get('cache_custom.enabled'),
            'ttl' => Config::get('cache_custom.ttl'),
            'debug' => Config::get('cache_custom.debug'),
            'allow_flush' => Config::get('cache_custom.allow_flush'),
            'driver' => Config::get('cache.default'),
            'original_store' => Config::get('cache_custom._original_store'),
            'modules' => Config::get('cache_custom.modules', []),
        ];
    }

    /**
     * Get TTL for a specific module or fallback to global ttl
     */
    public static function ttlFor(string $module): int
    {
        $modules = Config::get('cache_custom.modules', []);
        return (int) ($modules[$module]['ttl'] ?? Config::get('cache_custom.ttl', 3600));
    }
}
