<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'shop_id',
        'reserve_date',
        'reserve_time',
        'number_of_people',
        'note',
    ];

    protected $casts = [
        'reserve_date' => 'date', // Y-m-d
        'reserve_time' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    // 予約のJST日時（バリデーション整合チェックや並び替えに便利）
    public function startAt(): Carbon
    {
        $date = $this->reserve_date instanceof \DateTimeInterface
            ? $this->reserve_date->format('Y-m-d')
            : (string) $this->reserve_date;

        return Carbon::parse("{$date} {$this->reserve_time}", 'Asia/Tokyo');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('reserve_date', '>', now()->toDateString());
    }

    public function scopeFuture($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->where('reserve_date', '>', $now->toDateString())
                ->orWhere(function ($qq) use ($now) {
                    $qq->where('reserve_date', $now->toDateString())
                    ->where('reserve_time', '>=', $now->format('H:i:00'));
            });
        });
    }
}
