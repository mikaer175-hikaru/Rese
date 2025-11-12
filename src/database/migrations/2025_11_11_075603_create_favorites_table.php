<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();

            // users / shops へのFK（存在しないユーザー・店舗が消えたら一緒に削除）
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();

            $table->timestamps();

            // 同一ユーザーが同一店舗を重複登録できないように
            $table->unique(['user_id', 'shop_id']);
            // 逆向き検索の最適化（任意）
            $table->index(['shop_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
