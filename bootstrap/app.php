<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load admin routes first to avoid conflict with localized routes
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add SetLocale to web middleware group globally
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'admin' => \App\Modules\Admin\Middleware\AdminMiddleware::class,
            'cache.debug' => \App\Http\Middleware\CacheDebugMiddleware::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
            'localize.routes' => \App\Http\Middleware\LocalizeRoutes::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
