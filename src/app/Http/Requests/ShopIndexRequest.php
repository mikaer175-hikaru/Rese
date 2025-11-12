<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 一覧は誰でもOK（未認証ユーザーも通す）
        return true;
    }

    public function rules(): array
    {
        return [
            // 店名の部分一致キーワード
            'q'      => ['nullable','string','max:191'],

            // エリア/ジャンルは存在チェックまで行う
            'area'   => ['nullable','integer','exists:areas,id'],
            'genre'  => ['nullable','integer','exists:genres,id'],

            // ページネーションの安全対策（任意）
            'page'   => ['nullable','integer','min:1'],

            // もし1ページ件数を外部から渡したい場合（任意）
            'per_page' => ['nullable','integer','min:1','max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.max'           => '店名キーワードは191文字以内で入力してください。',
            'q.string'        => '店名キーワードは文字列で入力してください。',
            'area.integer'    => 'エリアの指定が不正です。',
            'area.exists'     => '選択されたエリアは存在しません。',
            'genre.integer'   => 'ジャンルの指定が不正です。',
            'genre.exists'    => '選択されたジャンルは存在しません。',
            'page.integer'    => 'ページ番号が不正です。',
            'page.min'        => 'ページ番号が不正です。',
            'per_page.integer'=> '1ページの表示件数が不正です。',
            'per_page.min'    => '1ページの表示件数が小さすぎます。',
            'per_page.max'    => '1ページの表示件数が大きすぎます。',
        ];
    }

    public function attributes(): array
    {
        return [
            'q'        => '店名キーワード',
            'area'     => 'エリア',
            'genre'    => 'ジャンル',
            'page'     => 'ページ番号',
            'per_page' => '表示件数',
        ];
    }

    /**
     * 前処理：空文字→null、全角英数→半角などの軽サニタイズ
     */
    protected function prepareForValidation(): void
    {
        // 万が一 "keyword" という名前で飛んできても受ける（後方互換）
        $q = $this->input('q', $this->input('keyword'));

        // 全角英数を半角に、前後空白をtrim
        $q = is_string($q) ? trim(mb_convert_kana($q, 'as')) : $q;

        $area  = $this->input('area');
        $genre = $this->input('genre');

        $this->merge([
            'q'        => $q === '' ? null : $q,
            'area'     => $area === '' ? null : $area,
            'genre'    => $genre === '' ? null : $genre,
            'per_page' => $this->input('per_page') ?: null,
        ]);
    }
}
