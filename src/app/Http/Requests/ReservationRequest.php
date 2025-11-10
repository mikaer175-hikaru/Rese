<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reserve_date'       => ['required', 'date', 'after_or_equal:today'],
            'reserve_time'       => ['required', 'date_format:H:i'],
            'number_of_people'   => ['required', 'integer', 'min:1', 'max:20'],
            'note'               => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'reserve_date.required'      => '予約日を入力してください。',
            'reserve_date.date'          => '予約日は日付形式で入力してください。',
            'reserve_date.after_or_equal'=> '予約日は本日以降を指定してください。',
            'reserve_time.required'      => '予約時間を入力してください。',
            'reserve_time.date_format'   => '予約時間は「HH:MM」形式で入力してください。',
            'number_of_people.required'  => '人数を入力してください。',
            'number_of_people.integer'   => '人数は半角数字で入力してください。',
            'number_of_people.min'       => '人数は1名以上で入力してください。',
            'number_of_people.max'       => '人数は20名以下で入力してください。',
            'note.string'                => 'メモは文字列で入力してください。',
            'note.max'                   => 'メモは255文字以内で入力してください。',
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

    /**
     * 予約日+時間の整合: 現在時刻より未来であること
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $date = $this->input('reserve_date');
            $time = $this->input('reserve_time');

            if (!$date || !$time) {
                return;
            }

            try {
                $reservedAt = Carbon::parse($date . ' ' . $time);
                if ($reservedAt->lessThanOrEqualTo(Carbon::now())) {
                    $v->errors()->add('reserve_time', '予約日時は現在時刻より後の時刻を指定してください。');
                }
            } catch (\Throwable $e) {
                $v->errors()->add('reserve_time', '予約日時の形式が不正です。');
            }
        });
    }
}
