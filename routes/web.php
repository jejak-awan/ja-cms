<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [PublicController::class, 'index'])->name('home');

// Articles
Route::get('/articles', [PublicController::class, 'articles'])->name('articles.index');
Route::get('/articles/{slug}', [PublicController::class, 'article'])->name('articles.show');

// Categories
Route::get('/categories', [PublicController::class, 'categories'])->name('categories.index');
Route::get('/categories/{slug}', [PublicController::class, 'category'])->name('categories.show');

// Pages
Route::get('/pages/{slug}', [PublicController::class, 'page'])->name('page.show');

// Search
Route::get('/search', [PublicController::class, 'search'])->name('search');

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// API Routes
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
| Admin Routes
|--------------------------------------------------------------------------
| All admin routes are defined in routes/admin.php
| They use 'admin' middleware with proper role checking
*/

/*
|--------------------------------------------------------------------------
| Pages Route (Catch-all - must be last!)
|--------------------------------------------------------------------------
| This route catches all remaining URLs and treats them as pages
| Make sure this is the LAST route defined
| Exclude 'admin' from catch-all to prevent conflicts with admin routes
*/

Route::get('/{slug}', [PublicController::class, 'page'])
    ->where('slug', '^(?!admin).*')
    ->name('pages.show');
