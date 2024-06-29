<?php
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::group(['name' => 'auth.'], function () {
        Route::post('/user/create', [\App\Http\Controllers\API\V1\AuthController::class, 'createUser'])->name('user.create');
    });
});
