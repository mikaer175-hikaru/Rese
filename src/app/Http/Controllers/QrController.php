<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QrController extends Controller
{
    // マイページの予約詳細にQRを出す
    public function showMyReservation(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('mypage.index')->with('error', '権限がありません。');
        }

        // 予約固有のトークン（なければ発行）
        if (empty($reservation->qr_token)) {
            $reservation->qr_token = Str::uuid()->toString(); // 予約IDとは別にランダムトークン
            $reservation->save();
        }

        // QRが指すURL（店舗側が照合で開く）
        $verifyUrl = route('qr.verify', ['token' => $reservation->qr_token]);

        return view('reservations.show', [
            'reservation' => $reservation,
            'verifyUrl'   => $verifyUrl,
        ]);
    }

    // 店舗代表者側：QR照合
    public function verify(string $token)
    {
        $reservation = Reservation::where('qr_token', $token)->with(['shop','user'])->first();

        if (!$reservation) {
            return view('qr.invalid'); // 「無効なQRコードです」を出す簡易ビュー
        }

        return view('qr.verify', ['reservation' => $reservation]);
    }
}
