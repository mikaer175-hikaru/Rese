<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('reminder:reservations-today')
    ->dailyAt('09:00')
    ->timezone('Asia/Tokyo')
    ->withoutOverlapping(30)     // 30分は重複実行させない
    ->onOneServer();             // 複数台構成でも1台だけ実行（※後述のキャッシュ必須）