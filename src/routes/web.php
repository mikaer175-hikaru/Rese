<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{shop}', [ShopController::class, 'show'])->name('shops.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/mypage', fn () => view('mypage.index'))->name('mypage');
});

Route::middleware('auth')->whereNumber('shop')->group(function () {
    Route::post('/favorites/{shop}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{shop}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

Route::post('/reservations', [ReservationController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('reservations.store');

Route::get('/done', [ReservationController::class, 'done'])
    ->name('reservations.done');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
    ->whereNumber('reservation')
    ->name('reservations.destroy');

// 👇 オーナー用
Route::prefix('owner')
    ->name('owner.')
    ->middleware(['auth', 'role:owner'])
    ->group(function () {
        Route::get('/', [OwnerDashboardController::class, 'index'])
            ->name('dashboard');

        // 予約一覧・店舗編集などはこの中に生やす
        // Route::get('/reservations', ...);
        // Route::get('/shops', ...);
    });

// 👇 管理者用
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // 店舗代表者アカウント管理・お知らせメール送信など
        // Route::get('/owners', ...);
        // Route::get('/notifications/create', ...);
    });
});