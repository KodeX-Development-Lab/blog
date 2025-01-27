<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;
use App\Http\Middleware\JwtMiddleware;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('v1/auth/')->group(function () {
    Route::post('register', [JWTAuthController::class, 'register']);
    Route::post('login', [JWTAuthController::class, 'login']);

    Route::middleware([JwtMiddleware::class])->group(function () {
        Route::get('user', [JWTAuthController::class, 'getUser']);
        Route::post('logout', [JWTAuthController::class, 'logout']);
    });

    // Route::post('/register', [RegisteredUserController::class, 'store'])
    //     ->middleware('guest')
    //     ->name('register');

    // Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    //     ->middleware('guest')
    //     ->name('login');

    // Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    //     ->middleware('guest')
    //     ->name('password.email');

    // Route::post('/reset-password', [NewPasswordController::class, 'store'])
    //     ->middleware('guest')
    //     ->name('password.store');

    // Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    //     ->middleware(['auth', 'signed', 'throttle:6,1'])
    //     ->name('verification.verify');

    // Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //     ->middleware(['auth', 'throttle:6,1'])
    //     ->name('verification.send');

    // Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    //     ->middleware('auth')
    //     ->name('logout');
});
