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
        'qr_token',
        'visited_at',
        'payment_method',
        'payment_status',
        'amount',
        'currency',
        'stripe_payment_intent_id',
        'stripe_checkout_session_id',
        'status',
    ];

    protected $casts = [
        'reserve_date' => 'date:Y-m-d',
        'reserve_time' => 'string',
        'visited_at'   => 'datetime',
    ];

    // -------- リレーション --------
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // -------- 便利アクセサ/メソッド --------
    /** HH:MM に丸めて取得 */
    public function getReserveTimeHmAttribute(): string
    {
        return substr((string) $this->reserve_time, 0, 5);
    }

    /** 予約のローカル日時（比較・並び替え用） */
    public function startAt(): Carbon
    {
        $tz   = config('app.timezone', 'Asia/Tokyo');
        $date = $this->reserve_date instanceof \DateTimeInterface
            ? $this->reserve_date->format('Y-m-d')
            : (string) $this->reserve_date;

        return Carbon::parse("$date {$this->reserve_time_hm}", $tz);
    }

    /** 明日以降のみ（当日NG方針） */
    public function scopeUpcoming($query)
    {
        return $query->where('reserve_date', '>', now()->toDateString());
    }

    /** 日付→時刻の昇順ソート */
    public function scopeOrderBySchedule($query)
    {
        return $query->orderBy('reserve_date')->orderBy('reserve_time');
    }

    /** 当日NGなので、明日以降のみ編集可 */
    public function isEditable(): bool
    {
        return $this->reserve_date > now()->toDateString();
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'cancelled') {
            return 'キャンセル済み';
        }

        return '通常';
    }
}

