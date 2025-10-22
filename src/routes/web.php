<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    AuthController as WebAuthController,
    WatchDogController as WebWatchDogController,
    CurrencyController as WebCurrencyController,
    UserSettingsController
};

// Public Pages
Route::view('/', 'welcome')->name('home');

Route::prefix('currencies')->name('currencies.')->group(function () {
    Route::get('/', [WebCurrencyController::class, 'index'])->name('index');
    Route::get('/{coin}', [WebCurrencyController::class, 'show'])->name('show');
});

// Authentication
Route::middleware('web')->group(function () {
    Route::get('login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [WebAuthController::class, 'login'])->name('login.post');
    Route::get('register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [WebAuthController::class, 'register'])->name('register.post');
    Route::post('logout', [WebAuthController::class, 'logout'])->name('logout');
});

// Protected Routes
Route::middleware(['web'])->group(function () {
    Route::get('dashboard', [WebAuthController::class, 'dashboard'])->name('dashboard');

    Route::prefix('watchdogs')->name('watchdogs.')->group(function () {
        Route::get('/', [WebWatchDogController::class, 'index'])->name('index');
        Route::get('/create', [WebWatchDogController::class, 'create'])->name('create');
        Route::post('/', [WebWatchDogController::class, 'store'])->name('store');
        Route::get('/{watchdog}/edit', [WebWatchDogController::class, 'edit'])->name('edit');
        Route::put('/{watchdog}', [WebWatchDogController::class, 'update'])->name('update');
        Route::delete('/{watchdog}', [WebWatchDogController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [UserSettingsController::class, 'edit'])->name('edit');
        Route::post('/', [UserSettingsController::class, 'update'])->name('update');
    });
});
