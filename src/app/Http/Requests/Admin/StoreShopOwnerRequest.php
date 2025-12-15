<?php

// app/Http/Requests/Admin/StoreShopOwnerRequest.php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 管理者ログイン前提なので true でOK（ポリシーを使うならそこで制御）
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:191'],
            'email'                 => ['required', 'string', 'email', 'max:191', 'unique:shop_owners,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'shop_id'               => ['required', 'exists:shops,id'],
        ];
    }

    public function messages(): array
    {
        return [
            // 未入力
            'name.required'                  => 'お名前を入力してください',
            'email.required'                 => 'メールアドレスを入力してください',
            'password.required'              => 'パスワードを入力してください',
            'password_confirmation.required' => 'パスワードを入力してください',

            // メール形式
            'email.email'                    => '有効なメールアドレスを入力してください',

            // パスワードの規則
            'password.min'                   => 'パスワードは8文字以上で入力してください',
            'password_confirmation.min'      => 'パスワードは8文字以上で入力してください',

            // 確認用パスワードとの不一致
            'password.confirmed'             => 'パスワードと一致しません',

            // 店舗
            'shop_id.required'               => '店舗を選択してください',
            'shop_id.exists'                 => '選択した店舗が不正です',
        ];
    }
}
