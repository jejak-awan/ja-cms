<?php

use App\Support\CacheHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

describe('CacheHelper', function () {
    
    beforeEach(function () {
        Cache::flush();
        Config::set('cache_custom.enabled', true);
        Config::set('cache_custom.ttl', 3600);
    });
    
    test('remembers value with callback', function () {
        $key = 'test_key';
        $module = 'article';
        $expectedValue = 'test_value';
        
        $result = CacheHelper::remember($key, $module, 60, function () use ($expectedValue) {
            return $expectedValue;
        });
        
        expect($result)->toBe($expectedValue)
            ->and(Cache::has($key))->toBeTrue();
    });
    
    test('returns cached value on second call', function () {
        $key = 'test_counter';
        $module = 'article';
        $counter = 0;
        
        // First call
        $result1 = CacheHelper::remember($key, $module, 60, function () use (&$counter) {
            $counter++;
            return $counter;
        });
        
        // Second call should return cached value
        $result2 = CacheHelper::remember($key, $module, 60, function () use (&$counter) {
            $counter++;
            return $counter;
        });
        
        expect($result1)->toBe(1)
            ->and($result2)->toBe(1) // Same value (cached)
            ->and($counter)->toBe(1); // Callback executed only once
    });
    
    test('forgets cached value', function () {
        $key = 'test_forget';
        $module = 'article';
        
        // Store value
        CacheHelper::remember($key, $module, 60, function () {
            return 'value';
        });
        
        expect(Cache::has($key))->toBeTrue();
        
        // Forget value
        CacheHelper::forget($key);
        
        expect(Cache::has($key))->toBeFalse();
    });
    
    test('respects custom TTL', function () {
        $key = 'test_ttl';
        $module = 'article';
        $customTtl = 120;
        
        CacheHelper::remember($key, $module, $customTtl, function () {
            return 'value';
        });
        
        // Value should be cached
        expect(Cache::has($key))->toBeTrue();
    });
    
    test('bypasses cache when disabled', function () {
        Config::set('cache_custom.enabled', false);
        
        $key = 'test_disabled';
        $module = 'article';
        $counter = 0;
        
        // First call
        $result1 = CacheHelper::remember($key, $module, 60, function () use (&$counter) {
            $counter++;
            return $counter;
        });
        
        // Second call should execute callback again (not cached)
        $result2 = CacheHelper::remember($key, $module, 60, function () use (&$counter) {
            $counter++;
            return $counter;
        });
        
        expect($result1)->toBe(1)
            ->and($result2)->toBe(2) // Different value (not cached)
            ->and($counter)->toBe(2) // Callback executed twice
            ->and(Cache::has($key))->toBeFalse();
    });
});
