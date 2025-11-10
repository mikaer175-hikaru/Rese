<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;

Route::get('/', [ShopController::class,'index']);
Route::get('/detail/{shop}', [ShopController::class,'detail']);

Route::middleware('auth')->group(function () {
    Route::post('/shops/{shop}/favorite', [FavoriteController::class,'toggle'])->name('favorites.toggle');
    Route::post('/shops/{shop}/reservations', [ReservationController::class,'store'])->name('reservations.store');
    Route::delete('/reservations/{reservation}', [ReservationController::class,'destroy'])->name('reservations.destroy');
    Route::get('/mypage', [UserController::class,'mypage']);
    Route::get('/done', fn() => view('reservations.done'));
});

Route::view('/thanks', 'reservations.thanks');
