<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $reservations = Reservation::with('shop')
            ->where('user_id', $user->id)
            ->upcoming() // 明日以降のみ（当日NG）
            ->orderBy('reserve_date')
            ->orderBy('reserve_time')
            ->get();

        // QRトークン未発行の予約には割り当て（GET内副作用OKなら簡易にここで）
        foreach ($reservations as $resv) {
            if (empty($resv->qr_token)) {
                $resv->qr_token = Str::uuid()->toString();
                // timestampsを変えたくない場合は updateQuietly を使ってもOK（Laravel10+）
                $resv->save();
            }
        }

        $favoriteShops = $user->favoriteShops()->withPivot('created_at')->get();

        return view('users.mypage', [
            'user'          => $user,
            'reservations'  => $reservations,
            'favoriteShops' => $favoriteShops,
        ]);
    }
}