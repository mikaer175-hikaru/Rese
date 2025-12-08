<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        // owner ログイン＋roleミドルウェアで守る前提なので true でOK
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:191'],
            'description' => ['required', 'string', 'max:1000'],
            'area_id'     => ['required', 'integer', 'exists:areas,id'],
            'genre_id'    => ['required', 'integer', 'exists:genres,id'],
            // 新規作成時は必須、更新時は任意にするイメージ
            'image'       => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'file',
                'image',
                'mimes:jpeg,png',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => '店舗名を入力してください',
            'description.required' => '店舗説明を入力してください',
            'area_id.required'     => 'エリアを選択してください',
            'genre_id.required'    => 'ジャンルを選択してください',
            'image.required'       => '店舗画像を選択してください',
            'image.image'          => '店舗画像は画像ファイルを選択してください',
            'image.mimes'          => '店舗画像はjpegまたはpng形式でアップロードしてください',
            'image.max'            => '店舗画像のサイズが大きすぎます',
        ];
    }
}