<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Laravel CMS API',
        'version' => '1.0.0',
        'documentation' => url('/api/docs')
    ]);
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('rate.limit:register,5,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('rate.limit:login,5,1');
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

/*
|--------------------------------------------------------------------------
| Protected API Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'api', 'rate.limit:api,60,1'])->group(function () {
    
    // User routes - using existing module controller
    Route::prefix('users')->group(function () {
        Route::get('/', [App\Modules\User\Controllers\UserController::class, 'index']);
        Route::get('/{user}', [App\Modules\User\Controllers\UserController::class, 'show']);
        Route::put('/{user}', [App\Modules\User\Controllers\UserController::class, 'update']);
        Route::delete('/{user}', [App\Modules\User\Controllers\UserController::class, 'destroy']);
    });
    
    // Article routes - using existing module controller
    Route::prefix('articles')->group(function () {
        Route::get('/', [App\Modules\Article\Controllers\ArticleController::class, 'index']);
        Route::post('/', [App\Modules\Article\Controllers\ArticleController::class, 'store']);
        Route::get('/{article}', [App\Modules\Article\Controllers\ArticleController::class, 'show']);
        Route::put('/{article}', [App\Modules\Article\Controllers\ArticleController::class, 'update']);
        Route::delete('/{article}', [App\Modules\Article\Controllers\ArticleController::class, 'destroy']);
    });
    
    // Category routes - using existing module controller
    Route::prefix('categories')->group(function () {
        Route::get('/', [App\Modules\Category\Controllers\CategoryController::class, 'index']);
        Route::post('/', [App\Modules\Category\Controllers\CategoryController::class, 'store']);
        Route::get('/{category}', [App\Modules\Category\Controllers\CategoryController::class, 'show']);
        Route::put('/{category}', [App\Modules\Category\Controllers\CategoryController::class, 'update']);
        Route::delete('/{category}', [App\Modules\Category\Controllers\CategoryController::class, 'destroy']);
    });
    
    // Page routes - using existing module controller
    Route::prefix('pages')->group(function () {
        Route::get('/', [App\Modules\Page\Controllers\PageController::class, 'index']);
        Route::post('/', [App\Modules\Page\Controllers\PageController::class, 'store']);
        Route::get('/{page}', [App\Modules\Page\Controllers\PageController::class, 'show']);
        Route::put('/{page}', [App\Modules\Page\Controllers\PageController::class, 'update']);
        Route::delete('/{page}', [App\Modules\Page\Controllers\PageController::class, 'destroy']);
    });
    
    // Media routes - using existing module controller
    Route::prefix('media')->group(function () {
        Route::get('/', [App\Modules\Media\Controllers\MediaController::class, 'index']);
        Route::post('/upload', [App\Modules\Media\Controllers\MediaController::class, 'upload']);
        Route::get('/{media}', [App\Modules\Media\Controllers\MediaController::class, 'show']);
        Route::put('/{media}', [App\Modules\Media\Controllers\MediaController::class, 'update']);
        Route::delete('/{media}', [App\Modules\Media\Controllers\MediaController::class, 'destroy']);
    });
    
    // Settings routes (Admin only) - using existing module controller
    Route::middleware('admin')->prefix('settings')->group(function () {
        Route::get('/', [App\Modules\Setting\Controllers\SettingController::class, 'index']);
        Route::put('/', [App\Modules\Setting\Controllers\SettingController::class, 'update']);
    });
});

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::prefix('public')->group(function () {
    // Public articles
    Route::get('/articles', [App\Http\Controllers\Api\PublicController::class, 'articles']);
    Route::get('/articles/{article}', [App\Http\Controllers\Api\PublicController::class, 'article']);
    
    // Public categories
    Route::get('/categories', [App\Http\Controllers\Api\PublicController::class, 'categories']);
    Route::get('/categories/{category}', [App\Http\Controllers\Api\PublicController::class, 'category']);
    
    // Public pages
    Route::get('/pages', [App\Http\Controllers\Api\PublicController::class, 'pages']);
    Route::get('/pages/{page}', [App\Http\Controllers\Api\PublicController::class, 'page']);
    
    // Search
    Route::get('/search', [App\Http\Controllers\Api\PublicController::class, 'search']);
});
