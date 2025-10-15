<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use App\Modules\Setting\Models\Setting;

/**
 * Cache Manager
 * 
 * Manages cache configuration, settings, and provides
 * admin panel integration for cache management
 */
class CacheManager
{
    protected $cacheService;
    
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }
    
    /**
     * Get cache configuration
     */
    public function getConfig(): array
    {
        $configured_driver = Setting::get('cache.driver', config('cache.default', 'file'));
        $enabled = $this->isEnabled();
        $driver = $enabled ? $configured_driver : 'null';
        return [
            'enabled' => $enabled,
            'driver' => $driver,
            'ttl' => $this->getTtl(),
            'debug' => $this->getDebugMode(),
            'available_drivers' => $this->cacheService->getAvailableDrivers(),
            'last_driver' => Setting::get('cache.last_driver', 'file'),
        ];
    }
    
    /**
     * Update cache configuration
     */
    public function updateConfig(array $config): bool
    {
        try {
            // Handle driver update persistently
            $enabled = $config['enabled'] ?? true;
            $driver = $config['driver'] ?? 'file';
            $ttl = $config['ttl'] ?? 3600;
            $debug = $config['debug'] ?? false;

            if (!$enabled) {
                // Simpan driver sekarang untuk restore kalau enable lagi
                $current = config('cache.default', 'file');
                Setting::set('cache.last_driver', $current, 'cache', 'string');
                Setting::set('cache.enabled', false, 'cache', 'boolean');
                Setting::set('cache.driver', 'null', 'cache', 'string');
                $driver = 'null';
                // TTL dan debug tetap ikut config
                Setting::set('cache.ttl', $ttl, 'cache', 'integer');
                Setting::set('cache.debug', $debug, 'cache', 'boolean');
            } else {
                // Ambil driver yang di-set user, fallback ke last_driver jika driver == 'null'
                if ($driver === 'null') {
                    $last_driver = Setting::get('cache.last_driver', 'file') ?: 'file';
                    $driver = $last_driver;
                } else {
                    // Update last_driver setiap kali user pilih driver di posisi enabled
                    Setting::set('cache.last_driver', $driver, 'cache', 'string');
                }
                Setting::set('cache.enabled', true, 'cache', 'boolean');
                Setting::set('cache.driver', $driver, 'cache', 'string');
                Setting::set('cache.ttl', $ttl, 'cache', 'integer');
                Setting::set('cache.debug', $debug, 'cache', 'boolean');
            }

            // Update runtime config
            \Config::set('cache.default', $driver);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to update cache config', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Enable cache
     */
    public function enable(?string $forceDriver = null): bool
    {
        // Enable dengan driver yang diingin user, atau last_driver jika null
        $driver = $forceDriver ?: Setting::get('cache.last_driver', 'file');
        return $this->updateConfig([
            'enabled' => true,
            'driver' => $driver,
            'ttl' => $this->getTtl(),
            'debug' => $this->getDebugMode(),
        ]);
    }
    
    /**
     * Disable cache
     */
    public function disable(): bool
    {
        // Simpan driver sekarang lalu disable
        $current_driver = config('cache.default', 'file');
        return $this->updateConfig([
            'enabled' => false,
            'driver' => $current_driver, // disimpan ke last_driver oleh updateConfig
            'ttl' => $this->getTtl(),
            'debug' => $this->getDebugMode(),
        ]);
    }
    
    /**
     * Check if cache is enabled
     */
    public function isEnabled(): bool
    {
        try {
            return (bool) Setting::get('cache.enabled', true);
        } catch (\Exception $e) {
            // Fallback to config if Setting table doesn't exist yet
            return config('cache.default', 'file') !== 'null';
        }
    }
    
    /**
     * Get cache TTL
     */
    public function getTtl(): int
    {
        try {
            return (int) Setting::get('cache.ttl', 3600);
        } catch (\Exception $e) {
            // Fallback to default TTL
            return 3600;
        }
    }
    
    /**
     * Get debug mode
     */
    public function getDebugMode(): bool
    {
        try {
            return (bool) Setting::get('cache.debug', false);
        } catch (\Exception $e) {
            // Fallback to config
            return config('app.debug', false);
        }
    }
    
    /**
     * Get cache status
     */
    public function getStatus(): array
    {
        return [
            'enabled' => $this->isEnabled(),
            'driver' => config('cache.default', 'file'),
            'ttl' => $this->getTtl(),
            'debug' => $this->getDebugMode(),
            'stats' => $this->cacheService->getStats(),
            'driver_stats' => $this->cacheService->getDriverStats(),
        ];
    }
    
    /**
     * Clear all cache
     */
    public function clearAll(): bool
    {
        return $this->cacheService->flush();
    }
    
    /**
     * Clear cache by pattern
     */
    public function clearByPattern(string $pattern): bool
    {
        try {
            // For file cache, we can't easily clear by pattern
            if (config('cache.default') === 'file') {
                \Log::info('Pattern clearing not supported with file cache');
                return false;
            }
            
            // For Redis, we can use pattern matching
            if (config('cache.default') === 'redis' && class_exists('Redis')) {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $keys = $redis->keys($pattern);
                if (!empty($keys)) {
                    $redis->del($keys);
                    return true;
                }
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::warning('Failed to clear cache by pattern', ['pattern' => $pattern, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Warm up cache
     */
    public function warmUp(): array
    {
        return $this->cacheService->warmUp();
    }
    
    /**
     * Get cache performance metrics
     */
    public function getMetrics(): array
    {
        $stats = $this->cacheService->getStats();
        $driverStats = $this->cacheService->getDriverStats();
        
        return [
            'performance' => [
                'hit_rate' => $stats['hit_rate'],
                'total_operations' => $stats['total_operations'],
                'hits' => $stats['hits'],
                'misses' => $stats['misses'],
                'writes' => $stats['writes'],
                'deletes' => $stats['deletes'],
                'runtime' => $stats['runtime'],
            ],
            'driver' => $driverStats,
            'recommendations' => $this->getRecommendations($stats),
        ];
    }
    
    /**
     * Get performance recommendations
     */
    protected function getRecommendations(array $stats): array
    {
        $recommendations = [];
        
        if ($stats['hit_rate'] < 50) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Low cache hit rate. Consider increasing TTL or optimizing cache keys.',
                'action' => 'Review cache strategy and increase TTL for frequently accessed data.'
            ];
        }
        
        if ($stats['misses'] > $stats['hits']) {
            $recommendations[] = [
                'type' => 'info',
                'message' => 'More cache misses than hits. Consider warming up cache.',
                'action' => 'Use cache warm-up feature to preload frequently accessed data.'
            ];
        }
        
        if (config('cache.default') === 'file' && $stats['total_operations'] > 1000) {
            $recommendations[] = [
                'type' => 'suggestion',
                'message' => 'High cache activity detected. Consider upgrading to Redis for better performance.',
                'action' => 'Install Redis and update cache driver for better performance.'
            ];
        }
        
        return $recommendations;
    }
}
