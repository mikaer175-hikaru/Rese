<?php

// app/Http/Controllers/Admin/ShopOwnerController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShopOwnerRequest;
use App\Models\Shop;
use App\Models\ShopOwner;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class ShopOwnerController extends Controller
{
    public function index(): View
    {
        $shopOwners = ShopOwner::with('shop')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.shop_owners.index', [
            'shopOwners' => $shopOwners,
        ]);
    }

    public function create(): View
    {
        $shops = Shop::orderBy('name')->get();

        return view('admin.shop_owners.create', [
            'shops' => $shops,
        ]);
    }

    public function store(StoreShopOwnerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ShopOwner::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'shop_id'  => $validated['shop_id'],
        ]);

        return redirect()
            ->route('admin.shop_owners.index')
            ->with('status', '店舗代表者アカウントを作成しました。');
    }
}
