<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // 紐づき
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();

            // 予約基本情報
            $table->date('reserve_date');                        // 例: 2025-12-03
            $table->time('reserve_time');                        // 例: 17:00:00
            $table->unsignedTinyInteger('number_of_people');     // 1〜20想定
            $table->string('note', 255)->nullable();

            // ★ QR / 来店（ここにまとめて定義しておくと後がラク）
            $table->string('qr_token', 191)->nullable()->unique();
            $table->timestamp('visited_at')->nullable();

            // ▼ 決済方法: none | card
            $table->string('payment_method', 20)
                ->default('none')
                ->comment('支払い方法: none|card');

            // ▼ 決済状態: unpaid | pending | paid | failed
            $table->string('payment_status', 20)
                ->default('unpaid')
                ->comment('決済状態: unpaid|pending|paid|failed');

            // ▼ 金額・通貨
            $table->unsignedInteger('amount')
                ->default(0)
                ->comment('税込金額（JPYは1円単位）');

            $table->string('currency', 8)
                ->default('jpy')
                ->comment('通貨');

            // ▼ Stripe ID
            $table->string('stripe_payment_intent_id', 64)
                ->nullable()
                ->comment('Stripe PaymentIntent ID');

            $table->string('stripe_checkout_session_id', 64)
                ->nullable()
                ->comment('Stripe Checkout Session ID');

            $table->timestamps();

            // よく使う絞り込み用
            $table->index(['payment_status', 'shop_id'], 'idx_reservations_payment_status_shop');
            $table->index(['reserve_date', 'reserve_time']); // 予約一覧の並び替えなど
            $table->index(['status', 'shop_id'], 'idx_reservations_status_shop');// 予約状態＋店舗での絞り込み
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
