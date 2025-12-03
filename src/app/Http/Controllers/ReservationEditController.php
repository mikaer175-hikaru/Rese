<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationUpdateRequest;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReservationEditController extends Controller
{
    public function edit(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('mypage.index')->with('error', '権限がありません。');
        }
        if (!$reservation->isEditable()) {
            return redirect()->route('mypage.index')->with('error', '当日の予約は変更できません。');
        }

        return view('reservations.edit', ['reservation' => $reservation]);
    }

    public function update(ReservationUpdateRequest $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('mypage.index')->with('error', '権限がありません。');
        }
        if (!$reservation->isEditable()) {
            return redirect()->route('mypage.index')->with('error', '当日の予約は変更できません。');
        }

        $data = $request->validated();
        $reservation->update($data);

        return redirect()->route('reservations.show', $reservation)->with('success', '予約内容を更新しました。');
    }
}
