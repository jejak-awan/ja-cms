<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set API response headers
        $response = $next($request);
        
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('X-API-Version', '1.0');
        $response->headers->set('X-Powered-By', 'Laravel CMS API');
        
        return $response;
    }
}
