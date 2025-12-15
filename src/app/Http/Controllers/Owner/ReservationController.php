<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $owner = Auth::user();

        // オーナーが管理している店舗一覧
        $shops = Shop::where('owner_id', $owner->id)
            ->orderBy('name')
            ->get();

        $period  = $request->input('period', 'all'); // all|today|week|month
        $shopId  = $request->input('shop_id');       // 店舗ID
        $perPage = 10;

        $query = Reservation::with(['shop', 'user'])
            ->whereHas('shop', function ($q) use ($owner) {
                $q->where('owner_id', $owner->id);
            });

        $today = Carbon::today();

        // 期間フィルタ
        if ($period === 'today') {
            $query->whereDate('reserve_date', $today);
        } elseif ($period === 'week') {
            $query->whereBetween('reserve_date', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereBetween('reserve_date', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()]);
        }

        // 店舗フィルタ
        if (!empty($shopId)) {
            $query->where('shop_id', $shopId);
        }

        $reservations = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return view('owner.reservations.index', [
            'reservations' => $reservations,
            'shops'        => $shops,
            'period'       => $period,
            'shopId'       => $shopId,
        ]);
    }
}

