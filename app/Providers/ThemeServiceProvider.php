<?php

namespace App\Providers;

use App\Modules\Theme\Services\ThemeService;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ThemeService::class, function ($app) {
            return new ThemeService();
        });

        $this->app->alias(ThemeService::class, 'theme');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
