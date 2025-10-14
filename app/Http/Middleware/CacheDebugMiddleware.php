<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class CacheDebugMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Config::get('cache_custom.debug')) {
            // Tambahkan info cache ke header response
            $cacheStatus = [
                'enabled' => Config::get('cache_custom.enabled'),
                'ttl' => Config::get('cache_custom.ttl'),
                'driver' => Config::get('cache.default'),
                'allow_flush' => Config::get('cache_custom.allow_flush'),
            ];
            $response->headers->set('X-Cache-Status', json_encode($cacheStatus));
        }

        return $response;
    }
}
