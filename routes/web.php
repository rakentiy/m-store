<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    // Login
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')
        ->middleware('throttle:auth');

    Route::delete('/logout', 'logout')->name('logout');

    // Register
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')
        ->middleware('throttle:auth');

    Route::get('/forgot-password', 'showLinkRequestForm')
        ->middleware('guest')
        ->name('password.request');
    Route::post('/forgot-password', 'sendResetLink')
        ->name('password.email');

    Route::get('/reset-password/{token}', 'showResetForm')
        ->name('password.reset');
    Route::post('/reset-password', 'reset')
        ->name('password.update');

    Route::get('auth/socialite/github', 'github')
        ->name('socialite.github');

    Route::get('auth/socialite/github/callback', 'githubCallback')
        ->name('socialite.callback');
});

Route::get('/', HomeController::class)->name('home');
