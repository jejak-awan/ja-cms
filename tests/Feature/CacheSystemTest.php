<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Support\CacheHelper;
use App\Services\CacheService;
use App\Services\CacheManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CacheSystemTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear cache before each test
        Cache::flush();
    }

    /** @test */
    public function cache_helper_exists_and_is_usable()
    {
        // Test that CacheHelper class exists
        $this->assertTrue(class_exists(CacheHelper::class));
        
        // Test basic caching
        $result = CacheHelper::remember('test_key', 'test_module', 60, function() {
            return 'test_value';
        });
        
        $this->assertEquals('test_value', $result);
        $this->assertTrue(CacheHelper::has('test_key'));
    }

    /** @test */
    public function cache_helper_handles_modules()
    {
        // Store data with different modules
        CacheHelper::put('article_1', 'Article Data', 'article', 60);
        CacheHelper::put('category_1', 'Category Data', 'category', 60);
        
        $this->assertTrue(CacheHelper::has('article_1'));
        $this->assertTrue(CacheHelper::has('category_1'));
        
        $this->assertEquals('Article Data', CacheHelper::get('article_1'));
        $this->assertEquals('Category Data', CacheHelper::get('category_1'));
    }

    /** @test */
    public function cache_helper_can_forget_keys()
    {
        CacheHelper::put('temp_key', 'temp_value', 'test', 60);
        $this->assertTrue(CacheHelper::has('temp_key'));
        
        CacheHelper::forget('temp_key');
        $this->assertFalse(CacheHelper::has('temp_key'));
    }

    /** @test */
    public function cache_helper_can_flush_all()
    {
        CacheHelper::put('key1', 'value1', 'test', 60);
        CacheHelper::put('key2', 'value2', 'test', 60);
        CacheHelper::put('key3', 'value3', 'test', 60);
        
        $this->assertTrue(CacheHelper::has('key1'));
        $this->assertTrue(CacheHelper::has('key2'));
        $this->assertTrue(CacheHelper::has('key3'));
        
        CacheHelper::flush();
        
        $this->assertFalse(CacheHelper::has('key1'));
        $this->assertFalse(CacheHelper::has('key2'));
        $this->assertFalse(CacheHelper::has('key3'));
    }

    /** @test */
    public function cache_helper_handles_cache_miss()
    {
        $result = CacheHelper::get('non_existent_key', 'default_value');
        $this->assertEquals('default_value', $result);
    }

    /** @test */
    public function cache_helper_remember_works_correctly()
    {
        $callCount = 0;
        
        // First call - should execute callback
        $result1 = CacheHelper::remember('remember_test', 'test', 60, function() use (&$callCount) {
            $callCount++;
            return 'computed_value';
        });
        
        $this->assertEquals('computed_value', $result1);
        $this->assertEquals(1, $callCount);
        
        // Second call - should NOT execute callback (cached)
        $result2 = CacheHelper::remember('remember_test', 'test', 60, function() use (&$callCount) {
            $callCount++;
            return 'computed_value';
        });
        
        $this->assertEquals('computed_value', $result2);
        $this->assertEquals(1, $callCount); // Should still be 1, not 2
    }

    /** @test */
    public function cache_service_exists_and_works()
    {
        $cacheService = app(CacheService::class);
        
        $this->assertInstanceOf(CacheService::class, $cacheService);
        
        // Test set and get
        $cacheService->set('service_test', 'service_value', 60);
        $value = $cacheService->get('service_test');
        
        $this->assertEquals('service_value', $value);
    }

    /** @test */
    public function cache_service_tracks_metrics_correctly()
    {
        $cacheService = new CacheService();
        
        // Clear cache first
        Cache::flush();
        
        // Test cache miss
        $value1 = $cacheService->get('non_existent', 'default');
        $stats1 = $cacheService->getStats();
        
        $this->assertEquals(0, $stats1['hits']);
        $this->assertEquals(1, $stats1['misses']);
        
        // Test cache write
        $cacheService->set('test_key', 'test_value', 60);
        $stats2 = $cacheService->getStats();
        
        $this->assertEquals(1, $stats2['writes']);
        
        // Test cache hit
        $value2 = $cacheService->get('test_key');
        $stats3 = $cacheService->getStats();
        
        $this->assertEquals('test_value', $value2);
        $this->assertEquals(1, $stats3['hits']);
        $this->assertEquals(1, $stats3['misses']); // Should still be 1
    }

    /** @test */
    public function cache_service_remember_tracks_metrics()
    {
        $cacheService = new CacheService();
        Cache::flush();
        
        // First call - cache miss, then write
        $result1 = $cacheService->remember('remember_key', function() {
            return 'computed';
        }, 60);
        
        $stats1 = $cacheService->getStats();
        
        $this->assertEquals('computed', $result1);
        $this->assertEquals(0, $stats1['hits']); // First call is a miss
        $this->assertEquals(1, $stats1['misses']);
        $this->assertEquals(1, $stats1['writes']);
        
        // Second call - cache hit
        $result2 = $cacheService->remember('remember_key', function() {
            return 'computed';
        }, 60);
        
        $stats2 = $cacheService->getStats();
        
        $this->assertEquals('computed', $result2);
        $this->assertEquals(1, $stats2['hits']); // Second call is a hit
        $this->assertEquals(1, $stats2['misses']); // Still 1 miss
        $this->assertEquals(1, $stats2['writes']); // Still 1 write
    }

    /** @test */
    public function cache_service_calculates_hit_rate_correctly()
    {
        $cacheService = new CacheService();
        Cache::flush();
        
        // Set some values
        $cacheService->set('key1', 'value1', 60);
        $cacheService->set('key2', 'value2', 60);
        $cacheService->set('key3', 'value3', 60);
        
        // Get existing keys (hits)
        $cacheService->get('key1');
        $cacheService->get('key2');
        $cacheService->get('key3');
        
        // Get non-existent keys (misses)
        $cacheService->get('key4');
        $cacheService->get('key5');
        
        $stats = $cacheService->getStats();
        
        // 3 hits, 2 misses = 60% hit rate
        $this->assertEquals(3, $stats['hits']);
        $this->assertEquals(2, $stats['misses']);
        $this->assertEquals(60.0, $stats['hit_rate']);
    }

    /** @test */
    public function cache_manager_works()
    {
        $cacheManager = app(CacheManager::class);
        
        $this->assertInstanceOf(CacheManager::class, $cacheManager);
        
        // Test getStatus
        $status = $cacheManager->getStatus();
        
        $this->assertIsArray($status);
        $this->assertArrayHasKey('enabled', $status);
        $this->assertArrayHasKey('driver', $status);
        $this->assertArrayHasKey('stats', $status);
    }

    /** @test */
    public function cache_manager_returns_config()
    {
        $cacheManager = app(CacheManager::class);
        
        $config = $cacheManager->getConfig();
        
        $this->assertIsArray($config);
        $this->assertArrayHasKey('enabled', $config);
        $this->assertArrayHasKey('driver', $config);
        $this->assertArrayHasKey('ttl', $config);
        $this->assertArrayHasKey('available_drivers', $config);
    }

    /** @test */
    public function cache_manager_can_clear_all()
    {
        Cache::put('test1', 'value1', 60);
        Cache::put('test2', 'value2', 60);
        
        $this->assertTrue(Cache::has('test1'));
        $this->assertTrue(Cache::has('test2'));
        
        $cacheManager = app(CacheManager::class);
        $result = $cacheManager->clearAll();
        
        $this->assertTrue($result);
        $this->assertFalse(Cache::has('test1'));
        $this->assertFalse(Cache::has('test2'));
    }

    /** @test */
    public function cache_is_enabled_check_works()
    {
        $this->assertTrue(CacheHelper::isEnabled());
        
        // Null driver means disabled
        config(['cache.default' => 'null']);
        $this->assertFalse(CacheHelper::isEnabled());
        
        // File driver means enabled
        config(['cache.default' => 'file']);
        $this->assertTrue(CacheHelper::isEnabled());
    }

    /** @test */
    public function cache_helper_supports_tagging_check()
    {
        // File driver does not support tagging
        config(['cache.default' => 'file']);
        $this->assertFalse(CacheHelper::supportsTagging());
        
        // Redis supports tagging
        config(['cache.default' => 'redis']);
        $this->assertTrue(CacheHelper::supportsTagging());
        
        // Memcached supports tagging
        config(['cache.default' => 'memcached']);
        $this->assertTrue(CacheHelper::supportsTagging());
    }

    /** @test */
    public function cache_helper_handles_many_operations()
    {
        // Test putMany
        $values = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];
        
        $result = CacheHelper::putMany($values, 'test', 60);
        $this->assertTrue($result);
        
        // Test many
        $retrieved = CacheHelper::many(['key1', 'key2', 'key3', 'non_existent']);
        
        $this->assertEquals('value1', $retrieved['key1']);
        $this->assertEquals('value2', $retrieved['key2']);
        $this->assertEquals('value3', $retrieved['key3']);
        $this->assertNull($retrieved['non_existent']);
    }

    protected function tearDown(): void
    {
        // Clean up after tests
        Cache::flush();
        
        parent::tearDown();
    }
}

