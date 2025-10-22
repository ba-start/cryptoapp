<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController as ApiAuthController,
    WatchDogController as ApiWatchDogController,
    CurrencyController as ApiCurrencyController
};

Route::prefix('v1')->group(function () {

    // Public Currency API
    Route::get('currencies', [ApiCurrencyController::class, 'index'])->name('api.currencies.index');
    Route::get('currencies/{currency}', [ApiCurrencyController::class, 'show'])->name('api.currencies.show');

    // Token Management
    Route::post('issue-token', [ApiAuthController::class, 'issueToken'])->name('api.token.issue');
    Route::post('revoke-token', [ApiAuthController::class, 'revokeToken'])->name('api.token.revoke');

    // JWT-Protected WatchDog API
    Route::middleware('auth:api')->prefix('watchdogs')->name('api.watchdogs.')->group(function () {
        Route::get('/', [ApiWatchDogController::class, 'index'])->name('index');
        Route::get('/{watchdog}', [ApiWatchDogController::class, 'show'])->name('show');
        Route::post('/', [ApiWatchDogController::class, 'store'])->name('store');
        Route::put('/{watchdog}', [ApiWatchDogController::class, 'update'])->name('update');
        Route::delete('/{watchdog}', [ApiWatchDogController::class, 'destroy'])->name('destroy');
        Route::get('/check', [ApiWatchDogController::class, 'checkAndQueueWatchdogs'])->name('check');
    });

    // Auth Endpoints
    Route::prefix('auth')->name('api.auth.')->group(function () {
        Route::post('register', [ApiAuthController::class, 'register'])->name('register');
        Route::post('login', [ApiAuthController::class, 'login'])->name('login');

        Route::middleware('jwt.auth')->group(function () {
            Route::post('logout', [ApiAuthController::class, 'logout'])->name('logout');
            Route::post('refresh', [ApiAuthController::class, 'refresh'])->name('refresh');
            Route::get('me', [ApiAuthController::class, 'me'])->name('me');
        });
    });
});
