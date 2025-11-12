<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 未来（当日含む）の予約のみ
        $reservations = Reservation::with('shop')
            ->where('user_id', $user->id)
            ->upcoming()
            ->orderBy('reserve_date')
            ->orderBy('reserve_time')
            ->get();

        // お気に入り店舗（ピボットから shops を取得）
        $favoriteShops = $user->favoriteShops()
            ->withPivot('created_at')
            ->get();

        return view('mypage.index', [
            'user'           => $user,
            'reservations'   => $reservations,
            'favoriteShops'  => $favoriteShops,
        ]);
    }
}
