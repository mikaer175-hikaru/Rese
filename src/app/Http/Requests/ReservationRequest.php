<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認証ユーザーのみ許可（未ログインのとき弾く）
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // ▼ 当日NG（明日以降のみ）
            'reserve_date'       => ['required', 'date', 'after:today'],
            'reserve_time'       => ['required', 'date_format:H:i'],
            'number_of_people'   => ['required', 'integer', 'min:1', 'max:20'],
            'note'               => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'reserve_date.required'    => '予約日を入力してください。',
            'reserve_date.date'        => '予約日は日付形式で入力してください。',
            'reserve_date.after'       => '予約日は「本日より後の日付」を選択してください。',
            'reserve_time.required'    => '予約時間を入力してください。',
            'reserve_time.date_format' => '予約時間は「HH:MM」形式で入力してください。',
            'number_of_people.required' => '人数を入力してください。',
            'number_of_people.integer'  => '人数は半角数字で入力してください。',
            'number_of_people.min'      => '人数は1名以上で入力してください。',
            'number_of_people.max'      => '人数は20名以下で入力してください。',
            'note.string'               => 'メモは文字列で入力してください。',
            'note.max'                  => 'メモは255文字以内で入力してください。',
        ];
    }

    public function attributes(): array
    {
        return [
            'reserve_date'     => '予約日',
            'reserve_time'     => '予約時間',
            'number_of_people' => '人数',
            'note'             => 'メモ',
        ];
    }
}

