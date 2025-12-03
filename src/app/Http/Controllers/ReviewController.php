<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewStoreRequest;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewStoreRequest $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('mypage.index')->with('error', '権限がありません。');
        }
        if (is_null($reservation->visited_at)) {
            return redirect()->route('reservations.show', $reservation)->with('error', '来店後に評価できます。');
        }
        if ($reservation->review) {
            return redirect()->route('reservations.show', $reservation)->with('error', 'この予約は既に評価済みです。');
        }

        $data = $request->validated();

        Review::create([
            'reservation_id' => $reservation->id,
            'user_id'        => Auth::id(),
            'shop_id'        => $reservation->shop_id,
            'rating'         => $data['rating'],
            'comment'        => $data['comment'] ?? null,
        ]);

        return redirect()->route('reservations.show', $reservation)->with('success', '評価を登録しました。');
    }
}
