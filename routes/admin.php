<?php

use App\Modules\Admin\Controllers\AuthController;
use App\Modules\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here are all the admin panel routes. These routes are loaded by the
| RouteServiceProvider and all of them will have the "admin" prefix.
|
| Updated: October 14, 2025 - User Management System Routes Added
|
*/

// Admin Authentication Routes (Guest only)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Admin Authenticated Routes
    Route::middleware(['admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/activity-feed', [AdminController::class, 'activityFeed'])->name('activity.feed');
        
        // Media Upload (for TinyMCE)
        Route::post('/upload-image', [\App\Modules\Media\Controllers\MediaUploadController::class, 'uploadImage'])
            ->name('upload.image');
        Route::post('/upload-multiple', [\App\Modules\Media\Controllers\MediaUploadController::class, 'uploadMultiple'])
            ->name('upload.multiple');
        Route::delete('/delete-image', [\App\Modules\Media\Controllers\MediaUploadController::class, 'deleteImage'])
            ->name('delete.image');
        
        // Media Library
        Route::get('/media', [\App\Modules\Media\Controllers\MediaController::class, 'index'])
            ->name('media.index');
        Route::post('/media/upload', [\App\Modules\Media\Controllers\MediaController::class, 'upload'])
            ->name('media.upload');
        Route::put('/media/{media}', [\App\Modules\Media\Controllers\MediaController::class, 'update'])
            ->name('media.update');
        Route::delete('/media/{media}', [\App\Modules\Media\Controllers\MediaController::class, 'destroy'])
            ->name('media.destroy');
        Route::post('/media/bulk-delete', [\App\Modules\Media\Controllers\MediaController::class, 'bulkDelete'])
            ->name('media.bulk-delete');
        
        // Articles - bulk action must come before resource routes
        Route::post('/articles/bulk-action', [\App\Modules\Article\Controllers\ArticleController::class, 'bulkAction'])
            ->name('articles.bulk-action');
        
        // Articles CRUD
        Route::resource('articles', \App\Modules\Article\Controllers\ArticleController::class)
            ->names('articles');
        
        // Categories - special routes must come before resource routes
        Route::post('/categories/update-order', [\App\Modules\Category\Controllers\CategoryController::class, 'updateOrder'])
            ->name('categories.update-order');
        Route::post('/categories/{id}/toggle-status', [\App\Modules\Category\Controllers\CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');
        
        // Categories CRUD
        Route::resource('categories', \App\Modules\Category\Controllers\CategoryController::class)
            ->names('categories');
        
        // Pages - special routes must come before resource routes
        Route::post('/pages/update-order', [\App\Modules\Page\Controllers\PageController::class, 'updateOrder'])
            ->name('pages.update-order');
        Route::post('/pages/{id}/toggle-status', [\App\Modules\Page\Controllers\PageController::class, 'toggleStatus'])
            ->name('pages.toggle-status');
        
        // Pages CRUD
        Route::resource('pages', \App\Modules\Page\Controllers\PageController::class)
            ->names('pages');
        
        // Users Management
        Route::resource('users', \App\Modules\User\Controllers\UserController::class)
            ->names('users');
        
        // Settings
        Route::get('/settings', [\App\Modules\Setting\Controllers\SettingController::class, 'index'])
            ->name('settings.index');
        Route::put('/settings', [\App\Modules\Setting\Controllers\SettingController::class, 'update'])
            ->name('settings.update');
        
        // Menus
        Route::resource('menus', \App\Modules\Menu\Controllers\MenuController::class)
            ->names('menus');
        Route::post('/menus/{menu}/items', [\App\Modules\Menu\Controllers\MenuController::class, 'addItem'])
            ->name('menus.items.add');
        Route::put('/menus/{menu}/order', [\App\Modules\Menu\Controllers\MenuController::class, 'updateOrder'])
            ->name('menus.update-order');
        Route::put('/menu-items/{item}', [\App\Modules\Menu\Controllers\MenuController::class, 'updateItem'])
            ->name('menu-items.update');
        Route::delete('/menu-items/{item}', [\App\Modules\Menu\Controllers\MenuController::class, 'deleteItem'])
            ->name('menu-items.delete');
        
        // Themes
        Route::get('/themes', [\App\Modules\Theme\Controllers\ThemeController::class, 'index'])
            ->name('themes.index');
        Route::post('/themes/{theme}/activate', [\App\Modules\Theme\Controllers\ThemeController::class, 'activate'])
            ->name('themes.activate');
        Route::get('/themes/{theme}/settings', [\App\Modules\Theme\Controllers\ThemeController::class, 'settings'])
            ->name('themes.settings');
        Route::put('/themes/{theme}/settings', [\App\Modules\Theme\Controllers\ThemeController::class, 'updateSettings'])
            ->name('themes.settings.update');
        
        // Plugins
        Route::get('/plugins', [\App\Modules\Plugin\Controllers\PluginController::class, 'index'])
            ->name('plugins.index');
        Route::post('/plugins/{plugin}/toggle', [\App\Modules\Plugin\Controllers\PluginController::class, 'toggle'])
            ->name('plugins.toggle');
        Route::get('/plugins/{plugin}/settings', [\App\Modules\Plugin\Controllers\PluginController::class, 'settings'])
            ->name('plugins.settings');
        Route::put('/plugins/{plugin}/settings', [\App\Modules\Plugin\Controllers\PluginController::class, 'updateSettings'])
            ->name('plugins.settings.update');
    });
});
