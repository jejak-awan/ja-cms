<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Skip if user doesn't have 2FA enabled
        if (!$user || !$user->two_factor_enabled) {
            return $next($request);
        }
        
        // Skip if 2FA already verified in this session
        if (session('two_factor_verified')) {
            return $next($request);
        }
        
        // Skip 2FA verification routes
        if ($request->is('admin/two-factor*') || $request->is('logout')) {
            return $next($request);
        }
        
        // Redirect to 2FA verification
        return redirect()->route('admin.two-factor.verify');
    }
}
