<?php

namespace App\Modules\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Check if user is authenticated and has admin/editor role
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access admin panel.');
        }

        // Check if user has admin or editor role
        $user = auth()->user();
        
        if (!$user->hasAnyRole(['admin', 'editor', 'author'])) {
            abort(403, 'Unauthorized access to admin panel.');
        }

        return $next($request);
    }
}
