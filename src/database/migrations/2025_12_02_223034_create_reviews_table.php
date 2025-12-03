<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // 1予約1レビュー
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->unique('reservation_id');

            // 冗長だけど集計用に持っておく
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');      // 1〜5
            $table->string('comment', 255)->nullable(); // 任意
            $table->timestamps();

            $table->index(['shop_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
