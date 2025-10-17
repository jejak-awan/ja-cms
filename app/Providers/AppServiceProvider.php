<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\CacheHelper;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Observers\ArticleObserver;
use App\Modules\Category\Models\Category;
use App\Modules\Category\Observers\CategoryObserver;
use App\Modules\Page\Models\Page;
use App\Modules\Page\Observers\PageObserver;
use App\Modules\User\Models\User;
use App\Modules\User\Observers\UserObserver;
use App\Modules\Media\Models\Media;
use App\Modules\Media\Observers\MediaObserver;

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
        
        // Register localized route macros
    \App\Modules\Language\Support\LocalizedRouteMacros::register();
        
        // Register model observers
        Article::observe(ArticleObserver::class);
        Category::observe(CategoryObserver::class);
        Page::observe(PageObserver::class);
        User::observe(UserObserver::class);
        Media::observe(MediaObserver::class);
    }
}
