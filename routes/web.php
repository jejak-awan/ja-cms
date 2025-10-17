<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Public Routes (Localized)
|--------------------------------------------------------------------------
*/

// Homepage - localized
Route::localized('/', [PublicController::class, 'index']);

// Articles - localized
Route::localized('/articles', [PublicController::class, 'articles']);
Route::localized('/articles/{slug}', [PublicController::class, 'article']);

// Categories - localized
Route::localized('/categories', [PublicController::class, 'categories']);
Route::localized('/categories/{slug}', [PublicController::class, 'category']);

// Pages - localized
Route::localized('/pages/{slug}', [PublicController::class, 'page']);

// Search - localized
Route::localized('/search', [PublicController::class, 'search']);

// Sitemap (no localization needed)
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// API Routes (no localization needed)
Route::post('/api/articles/{id}/view', [PublicController::class, 'incrementView'])->name('api.articles.view');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Locale/Language Routes
|--------------------------------------------------------------------------
*/

Route::post('/locale/{locale}', [\App\Modules\Language\Controllers\LocaleController::class, 'switch'])
    ->name('locale.switch')
    ->where('locale', '[a-z]{2}');

Route::get('/api/locale/current', [\App\Modules\Language\Controllers\LocaleController::class, 'current'])
    ->name('locale.current');

// Translation Export API for JavaScript
Route::get('/api/translations/{domain?}', function ($domain = 'default') {
    $locale = request()->get('locale', app()->getLocale());
    $json = \App\Modules\Language\Services\TranslationService::exportToJson($domain, $locale);
    
    return response($json, 200)
        ->header('Content-Type', 'application/json')
        ->header('Cache-Control', 'public, max-age=3600');
})->name('translations.export');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Admin routes must be defined before catch-all routes to avoid conflicts
*/

// Admin Authentication Routes (Guest only) - Redirect to main login
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', function () {
            return redirect()->route('login')->with('info', __('auth.admin_login_redirect'));
        })->name('login');
        Route::post('/login', function () {
            return redirect()->route('login');
        })->name('login.post');
    });

    // Admin Authenticated Routes
    Route::middleware(['admin', 'cache.debug'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/', [\App\Modules\Admin\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [\App\Modules\Admin\Controllers\AdminController::class, 'dashboard']);
    });
});

/*
|--------------------------------------------------------------------------
| Pages Route (Catch-all - must be last!)
|--------------------------------------------------------------------------
| This route catches all remaining URLs and treats them as pages
| Make sure this is the LAST route defined
| Exclude 'admin' from catch-all to prevent conflicts with admin routes
*/

// Localized catch-all for pages (exclude 'admin' to prevent conflicts)
Route::localized('/{slug}', [PublicController::class, 'page']);
