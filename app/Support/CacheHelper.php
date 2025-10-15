<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Cache Helper
 * 
 * Provides a simple cache interface with module-based tagging
 * for better cache invalidation and organization.
 */
class CacheHelper
{
    /**
     * Remember a value in cache with module tag
     * 
     * @param string $key Cache key
     * @param string $module Module name for tagging (e.g., 'article', 'category', 'page')
     * @param int $ttl Time to live in seconds
     * @param callable $callback Callback to generate value if not cached
     * @return mixed
     */
    public static function remember(string $key, string $module, int $ttl, callable $callback)
    {
        try {
            // Check if cache is enabled
            if (!static::isEnabled()) {
                return $callback();
            }
            
            // Try to use tags if supported (Redis, Memcached)
            if (static::supportsTagging()) {
                return Cache::tags([$module])->remember($key, $ttl, $callback);
            }
            
            // Fallback to regular cache without tags
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::warning('CacheHelper::remember failed', [
                'key' => $key,
                'module' => $module,
                'error' => $e->getMessage()
            ]);
            
            // Return fresh data on cache failure
            return $callback();
        }
    }
    
    /**
     * Store a value in cache with module tag
     * 
     * @param string $key Cache key
     * @param mixed $value Value to cache
     * @param string $module Module name for tagging
     * @param int $ttl Time to live in seconds
     * @return bool
     */
    public static function put(string $key, $value, string $module, int $ttl = 3600): bool
    {
        try {
            if (!static::isEnabled()) {
                return false;
            }
            
            if (static::supportsTagging()) {
                return Cache::tags([$module])->put($key, $value, $ttl);
            }
            
            return Cache::put($key, $value, $ttl);
        } catch (\Exception $e) {
            Log::warning('CacheHelper::put failed', [
                'key' => $key,
                'module' => $module,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Get a value from cache
     * 
     * @param string $key Cache key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        try {
            if (!static::isEnabled()) {
                return $default;
            }
            
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::warning('CacheHelper::get failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return $default;
        }
    }
    
    /**
     * Forget a specific cache key
     * 
     * @param string $key Cache key to forget
     * @return bool
     */
    public static function forget(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::warning('CacheHelper::forget failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Flush cache by module tag
     * 
     * @param string $module Module name to flush
     * @return bool
     */
    public static function flushByModule(string $module): bool
    {
        try {
            if (!static::supportsTagging()) {
                Log::info('Cache tagging not supported, cannot flush by module', ['module' => $module]);
                return false;
            }
            
            Cache::tags([$module])->flush();
            return true;
        } catch (\Exception $e) {
            Log::warning('CacheHelper::flushByModule failed', [
                'module' => $module,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Flush all cache
     * 
     * @return bool
     */
    public static function flush(): bool
    {
        try {
            Cache::flush();
            return true;
        } catch (\Exception $e) {
            Log::error('CacheHelper::flush failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Check if cache is enabled
     * 
     * @return bool
     */
    public static function isEnabled(): bool
    {
        $driver = config('cache.default', 'file');
        return $driver !== 'null' && $driver !== null;
    }
    
    /**
     * Check if current cache driver supports tagging
     * 
     * @return bool
     */
    public static function supportsTagging(): bool
    {
        $driver = config('cache.default', 'file');
        
        // Only Redis and Memcached support tagging
        return in_array($driver, ['redis', 'memcached']);
    }
    
    /**
     * Check if a key exists in cache
     * 
     * @param string $key Cache key
     * @return bool
     */
    public static function has(string $key): bool
    {
        try {
            return Cache::has($key);
        } catch (\Exception $e) {
            Log::warning('CacheHelper::has failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Get multiple cache keys at once
     * 
     * @param array $keys Array of cache keys
     * @param mixed $default Default value for missing keys
     * @return array
     */
    public static function many(array $keys, $default = null): array
    {
        try {
            if (!static::isEnabled()) {
                return array_fill_keys($keys, $default);
            }
            
            return Cache::many($keys);
        } catch (\Exception $e) {
            Log::warning('CacheHelper::many failed', [
                'keys' => $keys,
                'error' => $e->getMessage()
            ]);
            return array_fill_keys($keys, $default);
        }
    }
    
    /**
     * Store multiple cache items at once
     * 
     * @param array $values Key-value pairs to cache
     * @param string $module Module name for tagging
     * @param int $ttl Time to live in seconds
     * @return bool
     */
    public static function putMany(array $values, string $module, int $ttl = 3600): bool
    {
        try {
            if (!static::isEnabled()) {
                return false;
            }
            
            if (static::supportsTagging()) {
                return Cache::tags([$module])->putMany($values, $ttl);
            }
            
            return Cache::putMany($values, $ttl);
        } catch (\Exception $e) {
            Log::warning('CacheHelper::putMany failed', [
                'module' => $module,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

