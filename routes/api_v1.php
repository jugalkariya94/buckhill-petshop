<?php
use Illuminate\Support\Facades\Route;

// API v1 routes
Route::prefix('v1')->group(function () {

    // User auth routes
    Route::controller(\App\Http\Controllers\API\V1\AuthController::class)->group(function () {
        Route::post('/user/create', 'createUser')->name('user.create');
        Route::post('/user/login', 'login')->name('user.login');
        Route::get('/user/logout', 'logout')->name('user.logout');
        Route::post('/user/forgot-password', 'forgotPassword')->name('user.password.forgot');
        Route::post('/user/reset-password-token', 'resetPasswordToken')->name('user.password.reset');
        Route::middleware(['checkToken'])->group(function () {
            Route::get('/user', 'get')->name('user.get');
            Route::delete('/user', 'deleteUser')->name('user.delete');
            Route::put('/user/edit', 'updateUser')->name('user.update');
        });

    });
});
