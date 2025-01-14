<?php

use Illuminate\Support\Facades\Route;
use MyDDD\AuthDomain\Http\Controllers\V1\{
    OAuthController,
    PermissionController,
    RoleController,
    UserController
};
use MyDDD\AuthDomain\Http\Controllers\{
    RegisterController,
    TwoFactorController
};

Route::prefix(config('auth-domain.routes.prefix', 'auth'))
    ->middleware(config('auth-domain.routes.middleware', ['api']))
    ->name(config('auth-domain.routes.name', 'auth.'))
    ->group(function () {
        // Authentication Routes
        Route::post('register', [RegisterController::class, 'register'])->name('register');
        Route::post('login', [RegisterController::class, 'login'])->name('login');
        Route::post('logout', [RegisterController::class, 'logout'])
            ->middleware('auth:api')
            ->name('logout');
        Route::post('refresh', [RegisterController::class, 'refresh'])
            ->middleware('auth:api')
            ->name('refresh');

        // Password Reset Routes
        Route::post('password/email', [RegisterController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::post('password/reset', [RegisterController::class, 'reset'])->name('password.reset');

        // Email Verification Routes
        Route::post('email/verify/{id}/{hash}', [RegisterController::class, 'verify'])
            ->middleware(['auth:api', 'signed'])
            ->name('verification.verify');
        Route::post('email/resend', [RegisterController::class, 'resend'])
            ->middleware(['auth:api', 'throttle:6,1'])
            ->name('verification.resend');

        // Two Factor Authentication Routes
        Route::prefix('2fa')->group(function () {
            Route::post('enable', [TwoFactorController::class, 'enable'])
                ->middleware('auth:api')
                ->name('2fa.enable');
            Route::post('disable', [TwoFactorController::class, 'disable'])
                ->middleware('auth:api')
                ->name('2fa.disable');
            Route::post('verify', [TwoFactorController::class, 'verify'])
                ->name('2fa.verify');
        });

        // OAuth Routes
        Route::prefix('oauth')->group(function () {
            Route::get('{provider}', [OAuthController::class, 'redirect'])->name('oauth.redirect');
            Route::get('{provider}/callback', [OAuthController::class, 'callback'])->name('oauth.callback');
        });

        // Protected Routes
        Route::middleware(['auth:api'])->group(function () {
            // User Management
            Route::apiResource('users', UserController::class);
            
            // Role Management
            Route::apiResource('roles', RoleController::class);
            Route::post('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.permissions.sync');
            Route::post('roles/{role}/users', [RoleController::class, 'syncUsers'])->name('roles.users.sync');
            
            // Permission Management
            Route::apiResource('permissions', PermissionController::class);
            Route::post('permissions/generate', [PermissionController::class, 'generate'])->name('permissions.generate');
            Route::post('permissions/remove', [PermissionController::class, 'remove'])->name('permissions.remove');
        });
    });
