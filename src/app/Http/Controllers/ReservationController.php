<?php
// app/Http/Controllers/ReservationController.php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        // 予約はログイン＋メール認証済みが必須
        $this->middleware(['auth', 'verified'])->only(['store']);
    }

    /**
     * 予約保存 → 完了ページへ
     */
    public function store(ReservationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        Reservation::create($data);

        return redirect()
            ->route('reservations.done')
            ->with('status', '予約を受け付けました。');
    }

    /**
     * 予約更新
     */
    public function update(ReservationUpdateRequest $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('mypage.index')->with('error', '権限がありません。');
        }
        if (!$reservation->isEditable()) {
            return redirect()->route('mypage.index')->with('error', '当日の予約は変更できません。');
        }

        $reservation->update($request->validated());

        // ↓ 予約カードのアンカーへ戻す（マイページ内で“移動なし”の体感）
        return redirect()->to(route('mypage.index') . '#resv-' . $reservation->id)
            ->with('success', '予約内容を更新しました。');
    }

    /**
     * 完了ページ（静的ビュー）
     */
    public function done()
    {
        return view('reservations.done');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        // 本人の予約かチェック（アーリーリターン）
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('mypage.index')->with('error', '権限がありません。');
        }

        // 予約を削除（論理削除）
        $reservation->delete();

        return redirect()->route('mypage.index')->with('success', '予約をキャンセルしました。');
    }
}
