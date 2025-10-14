<?php

use App\Http\Middleware\CacheDebugMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

test('CacheDebugMiddleware → adds cache headers when debug enabled', function () {
    Config::set('cache_custom.debug', true);
    Config::set('cache_custom.enabled', true);
    Config::set('cache_custom.ttl', 3600);
    Config::set('cache.default', 'file');
    Config::set('cache_custom.allow_flush', true);

    $middleware = new CacheDebugMiddleware();
    $request = Request::create('/test');

    $response = $middleware->handle($request, function ($req) {
        return new Response('Test');
    });

    expect($response->headers->has('X-Cache-Status'))->toBeTrue();
    
    $cacheStatus = json_decode($response->headers->get('X-Cache-Status'), true);
    expect($cacheStatus['enabled'])->toBeTrue()
        ->and($cacheStatus['ttl'])->toBe(3600)
        ->and($cacheStatus['driver'])->toBe('file');
});

test('CacheDebugMiddleware → does not add headers when debug disabled', function () {
    Config::set('cache_custom.debug', false);

    $middleware = new CacheDebugMiddleware();
    $request = Request::create('/test');

    $response = $middleware->handle($request, function ($req) {
        return new Response('Test');
    });

    expect($response->headers->has('X-Cache-Status'))->toBeFalse();
});

test('CacheDebugMiddleware → passes request to next middleware', function () {
    Config::set('cache_custom.debug', false);

    $middleware = new CacheDebugMiddleware();
    $request = Request::create('/test');
    $called = false;

    $response = $middleware->handle($request, function ($req) use (&$called) {
        $called = true;
        return new Response('Test');
    });

    expect($called)->toBeTrue()
        ->and($response->getContent())->toBe('Test');
});
