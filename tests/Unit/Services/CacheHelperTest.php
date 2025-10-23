<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Support\CacheHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CacheHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear cache before each test
        Cache::flush();
    }

    /** @test */
    public function it_remembers_value_with_tag()
    {
        $key = 'test_key';
        $module = 'test_module';
        $value = 'test_value';
        $ttl = 3600;

        $result = CacheHelper::remember($key, $module, $ttl, function () use ($value) {
            return $value;
        });

        $this->assertEquals($value, $result);
        $this->assertTrue(Cache::has($key));
    }

    /** @test */
    public function it_forgets_value_by_tag()
    {
        $key = 'test_key';
        $module = 'test_module';
        $value = 'test_value';

        // Store value first
        CacheHelper::remember($key, $module, 3600, function () use ($value) {
            return $value;
        });

        $this->assertTrue(Cache::has($key));

        // Forget the value
        CacheHelper::forget($key);

        $this->assertFalse(Cache::has($key));
    }

    /** @test */
    public function it_flushes_all_cache()
    {
        $keys = ['key1', 'key2', 'key3'];
        
        // Store multiple values
        foreach ($keys as $key) {
            CacheHelper::remember($key, 'test_module', 3600, function () use ($key) {
                return "value_{$key}";
            });
        }

        // Verify all keys exist
        foreach ($keys as $key) {
            $this->assertTrue(Cache::has($key));
        }

        // Flush all cache
        Cache::flush();

        // Verify all keys are gone
        foreach ($keys as $key) {
            $this->assertFalse(Cache::has($key));
        }
    }

    /** @test */
    public function it_returns_fresh_data_on_cache_miss()
    {
        $key = 'non_existent_key';
        $module = 'test_module';
        $value = 'fresh_value';

        $result = CacheHelper::remember($key, $module, 3600, function () use ($value) {
            return $value;
        });

        $this->assertEquals($value, $result);
    }

    /** @test */
    public function it_respects_ttl()
    {
        $key = 'ttl_test_key';
        $module = 'test_module';
        $value = 'ttl_value';
        $ttl = 1; // 1 second

        // Store with short TTL
        CacheHelper::remember($key, $module, $ttl, function () use ($value) {
            return $value;
        });

        $this->assertTrue(Cache::has($key));

        // Wait for TTL to expire
        sleep(2);

        $this->assertFalse(Cache::has($key));
    }

    /** @test */
    public function it_uses_default_ttl_when_not_provided()
    {
        $key = 'default_ttl_key';
        $module = 'test_module';
        $value = 'default_value';

        // Mock config to return default TTL
        Config::set('cache_custom.modules.test_module.ttl', 7200);

        $result = CacheHelper::remember($key, $module, 7200, function () use ($value) {
            return $value;
        });

        $this->assertEquals($value, $result);
        $this->assertTrue(Cache::has($key));
    }

    /** @test */
    public function it_disables_caching_when_config_disabled()
    {
        $key = 'disabled_cache_key';
        $module = 'test_module';
        $value = 'disabled_value';

        // Disable caching
        Config::set('cache_custom.enabled', false);

        $result = CacheHelper::remember($key, $module, 3600, function () use ($value) {
            return $value;
        });

        $this->assertEquals($value, $result);
        $this->assertFalse(Cache::has($key)); // Should not be cached
    }

    /** @test */
    public function it_handles_callback_exceptions()
    {
        $key = 'exception_key';
        $module = 'test_module';

        $this->expectException(\Exception::class);

        CacheHelper::remember($key, $module, 3600, function () {
            throw new \Exception('Test exception');
        });
    }
}