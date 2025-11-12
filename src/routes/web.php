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

Route::middleware('auth')->group(function () {
    Route::post('/favorites/{shop}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{shop}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

Route::post('/reservations', [ReservationController::class, 'store'])
    ->name('reservations.store');

Route::get('/done', [ReservationController::class, 'done'])
    ->name('reservations.done');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
    ->whereNumber('reservation')
    ->name('reservations.destroy');
});