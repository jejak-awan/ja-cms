<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Http\Middleware\CacheDebugMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CacheDebugMiddlewareTest extends TestCase
{
    /** @test */
    public function it_adds_cache_debug_headers_when_debug_enabled()
    {
        // Enable cache debug
        Config::set('cache_custom.debug', true);
        Config::set('cache_custom.enabled', true);
        Config::set('cache_custom.ttl', 3600);
        Config::set('cache.default', 'file');
        Config::set('cache_custom.allow_flush', true);

        $middleware = new CacheDebugMiddleware();
        $request = Request::create('/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertTrue($response->headers->has('X-Cache-Status'));
        
        $cacheStatus = json_decode($response->headers->get('X-Cache-Status'), true);
        
        $this->assertArrayHasKey('enabled', $cacheStatus);
        $this->assertArrayHasKey('ttl', $cacheStatus);
        $this->assertArrayHasKey('driver', $cacheStatus);
        $this->assertArrayHasKey('allow_flush', $cacheStatus);
        
        $this->assertTrue($cacheStatus['enabled']);
        $this->assertEquals(3600, $cacheStatus['ttl']);
        $this->assertEquals('file', $cacheStatus['driver']);
        $this->assertTrue($cacheStatus['allow_flush']);
    }

    /** @test */
    public function it_does_not_add_headers_when_debug_disabled()
    {
        // Disable cache debug
        Config::set('cache_custom.debug', false);

        $middleware = new CacheDebugMiddleware();
        $request = Request::create('/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertFalse($response->headers->has('X-Cache-Status'));
    }

    /** @test */
    public function it_reflects_current_cache_configuration()
    {
        // Set specific cache configuration
        Config::set('cache_custom.debug', true);
        Config::set('cache_custom.enabled', false);
        Config::set('cache_custom.ttl', 7200);
        Config::set('cache.default', 'redis');
        Config::set('cache_custom.allow_flush', false);

        $middleware = new CacheDebugMiddleware();
        $request = Request::create('/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertTrue($response->headers->has('X-Cache-Status'));
        
        $cacheStatus = json_decode($response->headers->get('X-Cache-Status'), true);
        
        $this->assertFalse($cacheStatus['enabled']);
        $this->assertEquals(7200, $cacheStatus['ttl']);
        $this->assertEquals('redis', $cacheStatus['driver']);
        $this->assertFalse($cacheStatus['allow_flush']);
    }

    /** @test */
    public function it_handles_missing_cache_config()
    {
        // Clear cache config
        Config::set('cache_custom.debug', true);
        Config::set('cache_custom.enabled', null);
        Config::set('cache_custom.ttl', null);
        Config::set('cache.default', null);
        Config::set('cache_custom.allow_flush', null);

        $middleware = new CacheDebugMiddleware();
        $request = Request::create('/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertTrue($response->headers->has('X-Cache-Status'));
        
        $cacheStatus = json_decode($response->headers->get('X-Cache-Status'), true);
        
        $this->assertNull($cacheStatus['enabled']);
        $this->assertNull($cacheStatus['ttl']);
        $this->assertNull($cacheStatus['driver']);
        $this->assertNull($cacheStatus['allow_flush']);
    }

    /** @test */
    public function it_passes_response_through_unchanged()
    {
        Config::set('cache_custom.debug', true);

        $middleware = new CacheDebugMiddleware();
        $request = Request::create('/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('Test response');
        });

        $this->assertEquals('Test response', $response->getContent());
    }

    /** @test */
    public function it_uses_correct_config_keys()
    {
        Config::set('cache_custom.debug', true);
        Config::set('cache_custom.enabled', true);
        Config::set('cache_custom.ttl', 1800);
        Config::set('cache.default', 'database');
        Config::set('cache_custom.allow_flush', true);

        $middleware = new CacheDebugMiddleware();
        $request = Request::create('/test');
        
        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $cacheStatus = json_decode($response->headers->get('X-Cache-Status'), true);
        
        // Verify all expected keys are present
        $expectedKeys = ['enabled', 'ttl', 'driver', 'allow_flush'];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $cacheStatus);
        }
    }
}