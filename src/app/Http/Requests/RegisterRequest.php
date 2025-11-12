<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:191'],
            'email'                 => ['required', 'string', 'email', 'max:191', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'max:191', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'ユーザーネームを入力してください。',
            'email.required'        => 'メールアドレスを入力してください。',
            'email.email'           => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください。',
            'email.unique'          => 'このメールアドレスは既に登録されています。',
            'password.required'     => 'パスワードを入力してください。',
            'password.min'          => 'パスワードは8文字以上で入力してください。',
            'password.confirmed'    => '確認用パスワードが一致しません。',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'ユーザーネーム',
            'email'    => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }
}
