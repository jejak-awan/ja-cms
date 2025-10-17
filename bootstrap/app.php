<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load admin routes first to avoid conflict with localized routes
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add language detection to web middleware group globally
        $middleware->web(append: [
            \App\Modules\Language\Middleware\DetectLanguage::class,
            \App\Modules\Language\Middleware\SetLocale::class,
        ]);
        
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'admin' => \App\Modules\Admin\Middleware\AdminMiddleware::class,
            'cache.debug' => \App\Http\Middleware\CacheDebugMiddleware::class,
            'locale' => \App\Modules\Language\Middleware\SetLocale::class,
            'localize.routes' => \App\Modules\Language\Middleware\LocalizeRoutes::class,
            'api' => \App\Http\Middleware\ApiMiddleware::class,
            'security.headers' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'rate.limit' => \App\Http\Middleware\CustomRateLimiter::class,
            'two.factor' => \App\Http\Middleware\TwoFactorMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
