<?php

declare(strict_types=1);

namespace Domain\Auth\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

final class AuthRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')
            ->group(function () {
                Route::controller(LoginController::class)->group(function () {
                    // Login
                    Route::get('/login', 'showLoginForm')
                        ->name('login');
                    Route::post('/login', 'login')
                        ->middleware('throttle:auth');
                    Route::delete('/logout', 'logout')
                        ->name('logout');
                });

                Route::controller(RegisterController::class)->group(function () {
                    Route::get('/register', 'showRegisterForm')
                        ->name('register');
                    Route::post('/register', 'register')
                        ->middleware('throttle:auth');
                });

                Route::controller(ForgotPasswordController::class)->group(function () {
                    Route::get('/forgot-password', 'showLinkRequestForm')
                        ->middleware('guest')
                        ->name('password.request');
                    Route::post('/forgot-password', 'sendResetLink')
                        ->name('password.email');
                });

                Route::controller(ResetPasswordController::class)->group(function () {
                    Route::get('/reset-password/{token}', 'showResetForm')
                        ->name('password.reset');
                    Route::post('/reset-password', 'reset')
                        ->name('password.update');
                });

                Route::controller(SocialAuthController::class)->group(function () {
                    Route::get('auth/socialite/{driver}', 'redirect')
                        ->name('socialite');

                    Route::get('auth/socialite/{driver}/callback', 'callback')
                        ->name('socialite.callback');
                });
            });
    }
}
