<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 予約テーブル
     * - 当日不可／未来時刻などの業務ルールは FormRequest 側で検証
     * - DB では参照整合と検索効率を担保
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // 予約者・店舗
            $table->foreignId('user_id')->comment('予約したユーザーID')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->comment('対象の店舗ID')
                ->constrained()->cascadeOnDelete();

            // 日付と時刻は分離（検索や集計の柔軟性を優先）
            $table->date('reserve_date')->comment('予約日（YYYY-MM-DD）');
            $table->time('reserve_time')->comment('予約時刻（HH:MM:SS）');

            // 人数：アプリ側は 1〜20 を保証。DB は 0〜255 の範囲（unsignedTinyInteger）
            $table->unsignedTinyInteger('number_of_people')->comment('予約人数（アプリで1〜20を保証）');

            // 任意メモ
            $table->string('note', 255)->nullable()->comment('備考（任意）');

            $table->timestamps();

            // よく使う絞り込み用の複合インデックス
            $table->index(['user_id', 'reserve_date'], 'idx_reservations_user_date');
            $table->index(['shop_id', 'reserve_date', 'reserve_time'], 'idx_reservations_shop_datetime');

            // もし「同一ユーザーが同一日時に二重予約不可」を厳格化したい場合は UNIQUE も検討可
            // $table->unique(['user_id', 'reserve_date', 'reserve_time'], 'uk_user_datetime');
        });

        /**
         * ーー 拡張案（任意）ーー
         * 1) MySQL 8.0+ の CHECK 制約で 1〜20 をDB側でも保証したい場合：
         *
         * DB::statement("
         *   ALTER TABLE reservations
         *   ADD CONSTRAINT chk_reservations_people
         *   CHECK (number_of_people BETWEEN 1 AND 20)
         * ");
         *
         * 2) reserve_date + reserve_time を仮想の DATETIME 生成列にまとめて索引したい場合：
         *    （MySQL 8 の generated column 利用。環境によりエラーになるため任意）
         *
         * DB::statement("
         *   ALTER TABLE reservations
         *   ADD COLUMN reserved_at DATETIME
         *   GENERATED ALWAYS AS (STR_TO_DATE(CONCAT(reserve_date,' ',reserve_time), '%Y-%m-%d %H:%i:%s')) VIRTUAL
         * ");
         * DB::statement("CREATE INDEX idx_reservations_reserved_at ON reservations (reserved_at)");
         */
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
