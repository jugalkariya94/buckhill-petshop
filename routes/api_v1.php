<?php
use Illuminate\Support\Facades\Route;

// API v1 routes
Route::prefix('v1')->group(function () {

    // User auth routes
    Route::group(['name' => 'auth.'], function () {
        Route::get('/user', [\App\Http\Controllers\API\V1\AuthController::class, 'get'])->name('user.get')->middleware('auth:api');
        Route::post('/user/create', [\App\Http\Controllers\API\V1\AuthController::class, 'createUser'])->name('user.create');
        Route::post('/user/login', [\App\Http\Controllers\API\V1\AuthController::class, 'login'])->name('user.login');
        Route::get('/user/logout', [\App\Http\Controllers\API\V1\AuthController::class, 'logout'])->name('user.logout');
        Route::post('/user/forgot-password', [\App\Http\Controllers\API\V1\AuthController::class, 'forgotPassword'])->name('user.password.forgot');
        Route::post('/user/reset-password-token', [\App\Http\Controllers\API\V1\AuthController::class, 'resetPasswordToken'])->name('user.password.reset');
    });
});
