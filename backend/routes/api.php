<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Protected auth routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', function () {
        return auth()->user();
    });

    // Admin-only routes
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::apiResource('categories', CategoryController::class)
            ->names([
                'index' => 'api.categories.index',
                'show' => 'api.categories.show',
                'store' => 'api.categories.store',
                'destroy' => 'api.categories.destroy',
                'update' => 'api.categories.update',
            ]);

        Route::apiResource('products', ProductController::class)
            ->names([
                'index' => 'api.products.index',
                'show' => 'api.products.show',
                'store' => 'api.products.store',
                'destroy' => 'api.products.destroy',
                'update' => 'api.products.update',
            ]);
    });
});
