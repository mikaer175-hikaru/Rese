<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // 例: 寿司/焼肉/居酒屋/イタリアン/ラーメン
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};

