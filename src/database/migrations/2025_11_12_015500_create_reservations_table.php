<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // 決済方法: none | card
            $table->string('payment_method', 20)
                ->default('none')
                ->comment('支払い方法: none|card')
                ->after('note');

            // 決済状態: unpaid | pending | paid | failed
            $table->string('payment_status', 20)
                ->default('unpaid')
                ->comment('決済状態: unpaid|pending|paid|failed')
                ->after('payment_method');

            // 金額・通貨
            $table->unsignedInteger('amount')
                ->default(0)
                ->comment('税込金額（JPYは1円単位）')
                ->after('payment_status');

            $table->string('currency', 8)
                ->default('jpy')
                ->comment('通貨')
                ->after('amount');

            // Stripe ID
            $table->string('stripe_payment_intent_id', 64)
                ->nullable()
                ->comment('Stripe PaymentIntent ID')
                ->after('currency');

            $table->string('stripe_checkout_session_id', 64)
                ->nullable()
                ->comment('Stripe Checkout Session ID')
                ->after('stripe_payment_intent_id');

            // よく使う絞り込み用
            $table->index(['payment_status', 'shop_id'], 'idx_reservations_payment_status_shop');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex('idx_reservations_payment_status_shop');
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'amount',
                'currency',
                'stripe_payment_intent_id',
                'stripe_checkout_session_id',
            ]);
        });
    }
};
