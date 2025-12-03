<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // 作成時のみ必須。更新時は変更禁止（店の差し替え不可）
            'shop_id' => [
                Rule::requiredIf($this->isMethod('post')),
                Rule::prohibitedIf($this->isMethod('put') || $this->isMethod('patch')),
                'integer',
                'exists:shops,id',
            ],

            // 当日NG（明日以降）
            'reserve_date'       => ['required', 'date', 'after:today'],
            'reserve_time'       => ['required', 'date_format:H:i'],
            'number_of_people'   => ['required', 'integer', 'min:1', 'max:20'],
            'note'               => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'shop_id.required'   => '店舗が選択されていません。',
            'shop_id.exists'     => '選択した店舗が存在しません。',
            'shop_id.prohibited' => '店舗は変更できません。',

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
            'shop_id'           => '店舗',
            'reserve_date'      => '予約日',
            'reserve_time'      => '予約時間',
            'number_of_people'  => '人数',
            'note'              => 'メモ',
        ];
    }
}


