<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// password reset form
Route::get('/reset-password/{token}', function (string $token) {
    return 'your token for password reset is token' . $token;
})->middleware('guest')->name('password.reset');
