<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ShopController;

use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\ShopController as OwnerShopController;
use App\Http\Controllers\Owner\ReservationController as OwnerReservationController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ShopOwnerController;

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

    Route::get('/mypage', [MypageController::class, 'index'])
        ->middleware('verified')
        ->name('mypage.index');

    Route::post('/favorites/{shop}', [FavoriteController::class, 'store'])
        ->whereNumber('shop')
        ->name('favorites.store');

    Route::delete('/favorites/{shop}', [FavoriteController::class, 'destroy'])
        ->whereNumber('shop')
        ->name('favorites.destroy');

    Route::post('/reservations', [ReservationController::class, 'store'])
        ->middleware('verified')
        ->name('reservations.store');

    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->middleware('verified')
        ->whereNumber('reservation')
        ->name('reservations.destroy');
});

Route::get('/done', [ReservationController::class, 'done'])
    ->name('reservations.done');

// 👇 オーナー用（verified なしでOK、必要なら付ける）
Route::prefix('owner')
    ->name('owner.')
    ->middleware(['auth', 'role:owner'])
    ->group(function () {
        Route::get('/', [OwnerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/shops', [OwnerShopController::class, 'index'])
            ->name('shops.index');

        Route::get('/shops/create', [OwnerShopController::class, 'create'])
            ->name('shops.create');

        Route::post('/shops', [OwnerShopController::class, 'store'])
            ->name('shops.store');

        Route::get('/shops/{shop}/edit', [OwnerShopController::class, 'edit'])
            ->whereNumber('shop')
            ->name('shops.edit');

        Route::put('/shops/{shop}', [OwnerShopController::class, 'update'])
            ->whereNumber('shop')
            ->name('shops.update');

        Route::get('/reservations', [OwnerReservationController::class, 'index'])
            ->name('reservations.index');
    });

// 👇 管理者用
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/shop-owners', [ShopOwnerController::class, 'index'])
            ->name('shop_owners.index');

        Route::get('/shop-owners/create', [ShopOwnerController::class, 'create'])
            ->name('shop_owners.create');

        Route::post('/shop-owners', [ShopOwnerController::class, 'store'])
            ->name('shop_owners.store');
    });
