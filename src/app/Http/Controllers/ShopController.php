<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ShopIndexRequest;

class ShopController extends Controller
{
    /**
     * 店舗一覧（検索・フィルタ・ページネーション）
     */
    public function index(ShopIndexRequest $request)
    {
        $safe = $request->safe(); // バリデ通過後の入力のみ

        $q       = (string) ($safe->q ?? '');
        $areaId  = $safe->area ?? null;
        $genreId = $safe->genre ?? null;
        $perPage = $safe->per_page ?? 12;

        $query = Shop::query()
            ->with(['area:id,name','genre:id,name'])
            ->when(Auth::check(), fn($q2) =>
                $q2->withExists([
                    'favorites as is_favorited' => fn($q3) => $q3->where('user_id', Auth::id())
                ])
            )
            ->nameLike($q)
            ->areaId($areaId)
            ->genreId($genreId);

        $shops = $query->orderBy('id')
            ->paginate($perPage)
            ->appends($request->query());

        return view('shops.index', [
            'shops'   => $shops,
            'areas'   => Area::orderBy('name')->get(['id','name']),
            'genres'  => Genre::orderBy('name')->get(['id','name']),
            'q'       => $q,
            'areaId'  => (int) ($areaId ?? 0),
            'genreId' => (int) ($genreId ?? 0),
        ]);
    }

    /**
     * 店舗詳細
     */
    public function show(Shop $shop, Request $request)
    {
        $shop->load(['area', 'genre']);

        $futureReservations = $request->user()
            ? $request->user()
                ->reservations()
                ->with('shop')
                ->future()
                ->orderBy('reserve_date')
                ->orderBy('reserve_time')
                ->get()
            : collect();

        return view('shops.show', [
            'shop'               => $shop,
            'futureReservations' => $futureReservations,
        ]);
    }
}

