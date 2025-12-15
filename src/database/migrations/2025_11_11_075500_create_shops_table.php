<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();

            // オーナー（店舗代表者）
            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 基本情報
            $table->string('name', 191)->index();
            $table->string('image_url', 512)->nullable();
            $table->text('description')->nullable();

            // 紐づき（必ず存在するので外部キー制約つき）
            $table->foreignId('area_id')
                ->constrained()
                ->cascadeOnDelete()
                ->restrictOnDelete();
            $table->foreignId('genre_id')
                ->constrained()
                ->cascadeOnDelete()
                ->restrictOnDelete();

            $table->timestamps();

            // 絞り込み最適化
            $table->index(['area_id', 'genre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
