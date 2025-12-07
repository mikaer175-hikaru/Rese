<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\ShopRequest;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $owner = auth()->user();

        $shops = $owner->shops()
            ->with(['area', 'genre'])
            ->orderBy('id')
            ->paginate(10);

        return view('owner.shops.index', compact('shops'));
    }

    public function create(): View
    {
        $areas  = Area::orderBy('id')->get();
        $genres = Genre::orderBy('id')->get();

        return view('owner.shops.create', compact('areas', 'genres'));
    }

    public function store(ShopRequest $request): RedirectResponse
    {
        $owner = auth()->user();

        $data = $request->validated();
        $data['owner_id'] = $owner->id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shop_images', 'public');
            $data['image_path'] = $path; // shopsテーブル側のカラム名に合わせて
        }

        Shop::create($data);

        return redirect()
            ->route('owner.shops.index')
            ->with('status', '店舗を登録しました');
    }

    public function edit(Shop $shop): View
    {
        $this->authorizeOwner($shop);

        $areas  = Area::orderBy('id')->get();
        $genres = Genre::orderBy('id')->get();

        return view('owner.shops.edit', compact('shop', 'areas', 'genres'));
    }

    public function update(ShopRequest $request, Shop $shop): RedirectResponse
    {
        $this->authorizeOwner($shop);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($shop->image_path) {
                Storage::disk('public')->delete($shop->image_path);
            }

            $path = $request->file('image')->store('shop_images', 'public');
            $data['image_path'] = $path;
        }

        $shop->update($data);

        return redirect()
            ->route('owner.shops.index')
            ->with('status', '店舗情報を更新しました');
    }

    private function authorizeOwner(Shop $shop): void
    {
        $owner = auth()->user();

        if ($shop->owner_id !== $owner->id) {
            abort(403);
        }
    }
}

