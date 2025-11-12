<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * お気に入り登録（ログイン必須）
     */
    public function store(Shop $shop): RedirectResponse
    {
        Auth::user()->favoriteShops()->syncWithoutDetaching([$shop->id]);
        return back()->with('success', 'お気に入りに追加しました');
    }

    /**
     * お気に入り解除（ログイン必須）
     */
    public function destroy(Shop $shop): RedirectResponse
    {
        Auth::user()->favoriteShops()->detach($shop->id);
        return back()->with('success', 'お気に入りを解除しました');
    }
}
