<?php

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
| Note: Admin login is now handled by the unified login system.
| Admin routes redirect to the main login page.
|
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
    Route::middleware(['admin', 'cache.debug', 'security.headers'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
        
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
        
        // Users Management - bulk actions must come before resource routes
        Route::post('/users/bulk-action', [\App\Modules\User\Controllers\BulkUserController::class, 'bulkAction'])
            ->name('users.bulk-action');
        Route::post('/users/import', [\App\Modules\User\Controllers\ImportExportController::class, 'import'])
            ->name('users.import');
        Route::get('/users/export', [\App\Modules\User\Controllers\ImportExportController::class, 'export'])
            ->name('users.export');
        
        // Users CRUD
        Route::resource('users', \App\Modules\User\Controllers\UserController::class)
            ->names('users')
            ->where(['user' => '[0-9]+']);
        
        // User Profile Management
        Route::get('/profile', [\App\Modules\User\Controllers\ProfileController::class, 'show'])
            ->name('profile.show');
            Route::get('/users/{user}/profile', [\App\Modules\User\Controllers\ProfileController::class, 'showUserProfile'])
            ->name('users.profile.show');
        Route::get('/profile/edit', [\App\Modules\User\Controllers\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::get('/users/{user}/profile/edit', [\App\Modules\User\Controllers\ProfileController::class, 'editUserProfile'])
            ->name('users.profile.edit');
            Route::put('/users/{user}/profile', [\App\Modules\User\Controllers\ProfileController::class, 'updateUserProfile'])
                ->name('users.profile.update');
        Route::put('/profile', [\App\Modules\User\Controllers\ProfileController::class, 'update'])
            ->name('profile.update');
        Route::delete('/profile/delete-avatar', [\App\Modules\User\Controllers\ProfileController::class, 'deleteAvatar'])
            ->name('profile.delete-avatar');
        Route::get('/profile/change-password', [\App\Modules\User\Controllers\ProfileController::class, 'changePassword'])
            ->name('profile.change-password');
        Route::put('/profile/update-password', [\App\Modules\User\Controllers\ProfileController::class, 'updatePassword'])
            ->name('profile.update-password');
        
        // Roles Management
        Route::resource('roles', \App\Modules\User\Controllers\RoleController::class)
            ->names('roles');
        Route::post('/roles/{role}/toggle-status', [\App\Modules\User\Controllers\RoleController::class, 'toggleStatus'])
            ->name('roles.toggle-status');
        
        // Permissions Management
        Route::resource('permissions', \App\Modules\User\Controllers\PermissionController::class)
            ->names('permissions');
        
        // User Activity Logs
        Route::get('/users/activity-logs', [\App\Modules\User\Controllers\ActivityLogController::class, 'index'])
            ->name('users.activity-logs');
        Route::get('/users/activity-logs/statistics', [\App\Modules\User\Controllers\ActivityLogController::class, 'statistics'])
            ->name('users.activity-logs.statistics');
        Route::get('/users/activity-logs/export', [\App\Modules\User\Controllers\ActivityLogController::class, 'export'])
            ->name('users.activity-logs.export');
        Route::get('/users/activity-logs/{activityLog}', [\App\Modules\User\Controllers\ActivityLogController::class, 'show'])
            ->name('users.activity-logs.show');

        // User Import/Export
        Route::get('/users/import-export', [\App\Modules\User\Controllers\ImportExportController::class, 'index'])
            ->name('users.import-export');
        Route::post('/users/import-export/export', [\App\Modules\User\Controllers\ImportExportController::class, 'export'])
            ->name('users.import-export.export');
        Route::post('/users/import-export/import', [\App\Modules\User\Controllers\ImportExportController::class, 'import'])
            ->name('users.import-export.import');
        Route::get('/users/import-export/template', [\App\Modules\User\Controllers\ImportExportController::class, 'downloadTemplate'])
            ->name('users.import-export.template');

        // User Advanced Search
        Route::get('/users/search', [\App\Modules\User\Controllers\SearchController::class, 'index'])
            ->name('users.search');
        Route::post('/users/search', [\App\Modules\User\Controllers\SearchController::class, 'search'])
            ->name('users.search.post');
        Route::post('/users/search/export', [\App\Modules\User\Controllers\SearchController::class, 'export'])
            ->name('users.search.export');
        Route::post('/users/search/save', [\App\Modules\User\Controllers\SearchController::class, 'save'])
            ->name('users.search.save');
        Route::get('/users/search/saved', [\App\Modules\User\Controllers\SearchController::class, 'saved'])
            ->name('users.search.saved');

        // User Statistics
        Route::get('/users/statistics', [\App\Modules\User\Controllers\StatisticsController::class, 'index'])
            ->name('users.statistics');
        Route::get('/users/statistics/data', [\App\Modules\User\Controllers\StatisticsController::class, 'data'])
            ->name('users.statistics.data');
        Route::get('/users/statistics/export', [\App\Modules\User\Controllers\StatisticsController::class, 'export'])
            ->name('users.statistics.export');
        
        // Settings
        Route::get('/settings', [\App\Modules\Setting\Controllers\SettingController::class, 'index'])
            ->name('settings.index');
        Route::put('/settings', [\App\Modules\Setting\Controllers\SettingController::class, 'update'])
            ->name('settings.update');
        
        // Language Settings
        Route::get('/settings/languages', [\App\Modules\Admin\Controllers\LanguageSettingsController::class, 'index'])->name('settings.languages');
        Route::put('/settings/languages', [\App\Modules\Admin\Controllers\LanguageSettingsController::class, 'update'])->name('settings.languages.update');
        Route::post('/settings/languages/{id}/toggle', [\App\Modules\Admin\Controllers\LanguageSettingsController::class, 'toggleStatus'])->name('settings.languages.toggle');
        Route::post('/settings/languages/{id}/default', [\App\Modules\Admin\Controllers\LanguageSettingsController::class, 'setDefault'])->name('settings.languages.default');
        Route::post('/settings/languages/order', [\App\Modules\Admin\Controllers\LanguageSettingsController::class, 'updateOrder'])->name('settings.languages.order');
        Route::get('/settings/languages/statistics', [\App\Modules\Admin\Controllers\LanguageSettingsController::class, 'statistics'])->name('settings.languages.statistics');
        Route::post('/settings/languages/clear-cache', [\App\Modules\Admin\Controllers\LanguageSettingsController::class, 'clearCache'])->name('settings.languages.clear-cache');

        
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
        
        // Security Routes
        Route::prefix('security')->name('security.')->group(function () {
            Route::get('/two-factor', [\App\Http\Controllers\TwoFactorController::class, 'show'])->name('two-factor');
            Route::post('/two-factor/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('two-factor.enable');
            Route::post('/two-factor/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('two-factor.disable');
            Route::get('/two-factor/recovery-codes', [\App\Http\Controllers\TwoFactorController::class, 'recoveryCodes'])->name('two-factor.recovery-codes');
            Route::post('/two-factor/regenerate-codes', [\App\Http\Controllers\TwoFactorController::class, 'regenerateRecoveryCodes'])->name('two-factor.regenerate-codes');
        });
        
        // Performance Routes
        Route::prefix('performance')->name('performance.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PerformanceController::class, 'dashboard'])->name('dashboard');
            Route::get('/data', [\App\Http\Controllers\PerformanceController::class, 'metrics'])->name('data');
            Route::get('/cache-stats', [\App\Http\Controllers\PerformanceController::class, 'cacheStats'])->name('cache.stats');
            Route::post('/clear-cache', [\App\Http\Controllers\PerformanceController::class, 'clearCache'])->name('cache.clear');
            Route::post('/warm-cache', [\App\Http\Controllers\PerformanceController::class, 'warmUpCache'])->name('cache.warm');
            Route::post('/add-indexes', [\App\Http\Controllers\PerformanceController::class, 'addIndexes'])->name('database.indexes');
            Route::post('/optimize-database', [\App\Http\Controllers\PerformanceController::class, 'optimizeDatabase'])->name('database.optimize');
            Route::get('/database-stats', [\App\Http\Controllers\PerformanceController::class, 'databaseStats'])->name('database.stats');
            Route::post('/optimize-image', [\App\Http\Controllers\PerformanceController::class, 'optimizeImage'])->name('image.optimize');
            Route::post('/batch-optimize-images', [\App\Http\Controllers\PerformanceController::class, 'batchOptimizeImages'])->name('image.batch-optimize');
            Route::get('/report', [\App\Http\Controllers\PerformanceController::class, 'generateReport'])->name('report');
            Route::get('/recommendations', [\App\Http\Controllers\PerformanceController::class, 'recommendations'])->name('recommendations');
        });
    });
    
    // 2FA Verification Routes (separate from admin middleware)
    Route::middleware(['auth', 'two.factor'])->group(function () {
        Route::get('/two-factor/verify', function () {
            return view('admin.security.verify-two-factor');
        })->name('admin.two-factor.verify');
        Route::post('/two-factor/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify'])->name('admin.two-factor.verify');
    });
    
    // Cache Management Routes
    Route::prefix('cache')->name('cache.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CacheController::class, 'dashboard'])->name('dashboard');
        Route::get('/status', [\App\Http\Controllers\CacheController::class, 'status'])->name('status');
        Route::get('/stats', [\App\Http\Controllers\CacheController::class, 'stats'])->name('stats');
        Route::get('/metrics', [\App\Http\Controllers\CacheController::class, 'metrics'])->name('metrics');
        Route::get('/config', [\App\Http\Controllers\CacheController::class, 'config'])->name('config');
        Route::post('/config', [\App\Http\Controllers\CacheController::class, 'updateConfig'])->name('config.update');
        Route::post('/clear-all', [\App\Http\Controllers\CacheController::class, 'clearAll'])->name('clear-all');
        Route::post('/clear-pattern', [\App\Http\Controllers\CacheController::class, 'clearByPattern'])->name('clear-pattern');
        Route::post('/warm-up', [\App\Http\Controllers\CacheController::class, 'warmUp'])->name('warm-up');
        Route::post('/enable', [\App\Http\Controllers\CacheController::class, 'enable'])->name('enable');
        Route::post('/disable', [\App\Http\Controllers\CacheController::class, 'disable'])->name('disable');
    });
});
