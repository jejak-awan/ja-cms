<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\CacheHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind cache helper to container (optional DI usage)
        $this->app->singleton(CacheHelper::class, function () {
            return new CacheHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('Http/Helpers/theme_helpers.php');
    }
}
