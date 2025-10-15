<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * Unified Cache Service
 * 
 * Provides a comprehensive caching solution with:
 * - Multiple cache drivers support (file, database, redis, memcached)
 * - Cache tagging for better invalidation
 * - Performance metrics and monitoring
 * - Automatic fallback mechanisms
 * - Admin panel integration
 */
class CacheService
{
    protected $driver;
    protected $defaultTtl = 3600; // 1 hour
    protected $metrics = [];
    
    public function __construct()
    {
        $this->driver = config('cache.default', 'file');
        $this->initializeMetrics();
    }
    
    /**
     * Initialize cache metrics
     */
    protected function initializeMetrics()
    {
        $this->metrics = [
            'hits' => 0,
            'misses' => 0,
            'writes' => 0,
            'deletes' => 0,
            'start_time' => microtime(true),
        ];
    }
    
    /**
     * Get cache with automatic fallback
     */
    public function get(string $key, $default = null)
    {
        try {
            // Check if key exists first for accurate metrics
            if (Cache::has($key)) {
                $this->metrics['hits']++;
                return Cache::get($key);
            } else {
                $this->metrics['misses']++;
                return $default;
            }
        } catch (\Exception $e) {
            Log::warning('Cache get failed', ['key' => $key, 'error' => $e->getMessage()]);
            $this->metrics['misses']++;
            return $default;
        }
    }
    
    /**
     * Set cache with TTL
     */
    public function set(string $key, $value, int $ttl = null): bool
    {
        try {
            $ttl = $ttl ?? $this->defaultTtl;
            $result = Cache::put($key, $value, $ttl);
            if ($result) {
                $this->metrics['writes']++;
            }
            return $result;
        } catch (\Exception $e) {
            Log::warning('Cache set failed', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Remember cache with callback
     */
    public function remember(string $key, callable $callback, int $ttl = null)
    {
        try {
            $ttl = $ttl ?? $this->defaultTtl;
            
            // Check if key exists to properly track hits/misses
            $exists = Cache::has($key);
            
            if ($exists) {
                $this->metrics['hits']++;
            } else {
                $this->metrics['misses']++;
            }
            
            $value = Cache::remember($key, $ttl, $callback);
            
            // Count as write if it was a cache miss
            if (!$exists) {
                $this->metrics['writes']++;
            }
            
            return $value;
        } catch (\Exception $e) {
            Log::warning('Cache remember failed', ['key' => $key, 'error' => $e->getMessage()]);
            $this->metrics['misses']++;
            return $callback();
        }
    }
    
    /**
     * Cache with tags for better invalidation
     */
    public function rememberWithTags(string $key, array $tags, callable $callback, int $ttl = null)
    {
        try {
            $ttl = $ttl ?? $this->defaultTtl;
            return Cache::tags($tags)->remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::warning('Cache tags not supported, falling back to regular cache', ['error' => $e->getMessage()]);
            return $this->remember($key, $callback, $ttl);
        }
    }
    
    /**
     * Delete cache
     */
    public function forget(string $key): bool
    {
        try {
            $result = Cache::forget($key);
            if ($result) {
                $this->metrics['deletes']++;
            }
            return $result;
        } catch (\Exception $e) {
            Log::warning('Cache forget failed', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Delete cache by tags
     */
    public function forgetByTags(array $tags): bool
    {
        try {
            Cache::tags($tags)->flush();
            return true;
        } catch (\Exception $e) {
            Log::warning('Cache tags flush failed', ['tags' => $tags, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Clear all cache
     */
    public function flush(): bool
    {
        try {
            Cache::flush();
            $this->initializeMetrics();
            return true;
        } catch (\Exception $e) {
            Log::warning('Cache flush failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        $runtime = microtime(true) - $this->metrics['start_time'];
        $total = $this->metrics['hits'] + $this->metrics['misses'];
        $hitRate = $total > 0 ? round(($this->metrics['hits'] / $total) * 100, 2) : 0;
        
        return [
            'driver' => $this->driver,
            'hits' => $this->metrics['hits'],
            'misses' => $this->metrics['misses'],
            'writes' => $this->metrics['writes'],
            'deletes' => $this->metrics['deletes'],
            'hit_rate' => $hitRate,
            'runtime' => round($runtime, 2),
            'total_operations' => $total,
        ];
    }
    
    /**
     * Get driver-specific statistics
     */
    public function getDriverStats(): array
    {
        try {
            switch ($this->driver) {
                case 'redis':
                    if (class_exists('Redis') && config('cache.default') === 'redis') {
                        $redis = \Illuminate\Support\Facades\Redis::connection();
                        $info = $redis->info();
                        return [
                            'driver' => 'redis',
                            'version' => $info['redis_version'] ?? 'Unknown',
                            'memory_used' => $info['used_memory_human'] ?? 'Unknown',
                            'connected_clients' => $info['connected_clients'] ?? 0,
                            'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                            'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                        ];
                    }
                    break;
                    
                case 'database':
                    return [
                        'driver' => 'database',
                        'table' => config('cache.stores.database.table', 'cache'),
                        'connection' => config('cache.stores.database.connection'),
                    ];
                    
                case 'file':
                    $cachePath = config('cache.stores.file.path', storage_path('framework/cache/data'));
                    $size = 0;
                    if (is_dir($cachePath)) {
                        $size = $this->getDirectorySize($cachePath);
                    }
                    return [
                        'driver' => 'file',
                        'path' => $cachePath,
                        'size' => $this->formatBytes($size),
                        'size_bytes' => $size,
                    ];
                    
                default:
                    return [
                        'driver' => $this->driver,
                        'status' => 'Active',
                    ];
            }
        } catch (\Exception $e) {
            return [
                'driver' => $this->driver,
                'error' => $e->getMessage(),
            ];
        }
        
        return [
            'driver' => $this->driver,
            'status' => 'File cache (no advanced stats)',
        ];
    }
    
    /**
     * Warm up cache for frequently accessed data
     */
    public function warmUp(): array
    {
        $results = [];
        
        try {
            // Cache dashboard stats
            $results['dashboard_stats'] = $this->remember('cache.dashboard_stats', function () {
                return [
                    'total_articles' => \App\Modules\Article\Models\Article::count(),
                    'total_pages' => \App\Modules\Page\Models\Page::count(),
                    'total_users' => \App\Modules\User\Models\User::count(),
                    'total_categories' => \App\Modules\Category\Models\Category::count(),
                ];
            }, 3600);
            
            // Cache active categories
            $results['active_categories'] = $this->remember('cache.active_categories', function () {
                return \App\Modules\Category\Models\Category::where('is_active', true)
                    ->orderBy('order')
                    ->get();
            }, 1800);
            
            // Cache settings
            $results['settings'] = $this->remember('cache.settings', function () {
                return \App\Modules\Setting\Models\Setting::all()->pluck('value', 'key');
            }, 3600);
            
            return $results;
        } catch (\Exception $e) {
            Log::error('Cache warm up failed', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Get directory size recursively
     */
    protected function getDirectorySize(string $directory): int
    {
        $size = 0;
        if (is_dir($directory)) {
            foreach (glob($directory . '/*') as $file) {
                if (is_file($file)) {
                    $size += filesize($file);
                } elseif (is_dir($file)) {
                    $size += $this->getDirectorySize($file);
                }
            }
        }
        return $size;
    }
    
    /**
     * Format bytes to human readable
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Check if cache is enabled
     */
    public function isEnabled(): bool
    {
        return $this->driver !== 'null';
    }
    
    /**
     * Get available cache drivers
     */
    public function getAvailableDrivers(): array
    {
        $drivers = ['file', 'database', 'array'];
        
        if (class_exists('Redis')) {
            $drivers[] = 'redis';
        }
        
        if (class_exists('Memcached')) {
            $drivers[] = 'memcached';
        }
        
        return $drivers;
    }
}
