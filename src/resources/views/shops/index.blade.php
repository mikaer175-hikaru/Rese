{{-- resources/views/shops/index.blade.php --}}
@extends('layouts.app')
@section('title','店舗一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/pages/shops-index.css') }}">
@endsection

@section('content')
<section class="p-shop-list" aria-labelledby="shop-list-title">
    <h1 id="shop-list-title" class="p-shop-list__title">店舗を探す</h1>

    <form action="{{ route('shops.index') }}" method="get" class="p-shop-list__filter c-filter" role="search">
        <div class="c-filter__row">
            <div class="c-filter__field">
                <label class="c-filter__label" for="area">エリア</label>
                <select id="area" name="area" class="c-select">
                    <option value="">すべて</option>
                    @foreach($areas as $a)
                        <option value="{{ $a->id }}" @selected(request('area')==$a->id)>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="c-filter__field">
                <label class="c-filter__label" for="genre">ジャンル</label>
                <select id="genre" name="genre" class="c-select">
                    <option value="">すべて</option>
                    @foreach($genres as $g)
                        <option value="{{ $g->id }}" @selected(request('genre')==$g->id)>{{ $g->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="c-filter__field c-filter__field--grow">
                <label class="c-filter__label" for="q">店名</label>
                <input id="q" name="q" class="c-input" value="{{ request('q') }}" placeholder="店名で検索">
            </div>
            <div class="c-filter__actions">
                <button class="c-button">検索</button>
                <a href="{{ route('shops.index') }}" class="c-button c-button--ghost">リセット</a>
            </div>
        </div>
    </form>

    <ul class="p-shop-list__grid c-card-grid">
        @forelse($shops as $shop)
            <li class="c-card-grid__item">
                <article class="c-card">
                    <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" class="c-card__media">
                    <div class="c-card__body">
                        <h2 class="c-card__title">{{ $shop->name }}</h2>
                        <p class="c-card__meta">#{{ $shop->area->name }}　#{{ $shop->genre->name }}</p>
                        <p class="c-card__text">{{ \Illuminate\Support\Str::limit($shop->description, 60) }}</p>
                    </div>
                    <div class="c-card__actions">
                        <a class="c-button" href="{{ route('shops.detail', $shop) }}">詳しく見る</a>

                        @auth
                            @if(property_exists($shop,'is_favorited') ? $shop->is_favorited : $shop->favorites->where('user_id',auth()->id())->count())
                                <form action="{{ route('favorites.destroy',$shop) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="c-button c-button--ghost" aria-pressed="true">★ お気に入り中</button>
                                </form>
                            @else
                                <form action="{{ route('favorites.store',$shop) }}" method="post">
                                    @csrf
                                    <button class="c-button c-button--ghost" aria-pressed="false">☆ お気に入り</button>
                                </form>
                            @endif
                        @else
                            <a class="c-button c-button--ghost" href="{{ route('login') }}">☆ お気に入り</a>
                        @endauth
                    </div>
                </article>
            </li>
        @empty
            <li class="p-shop-list__empty">該当する店舗はありません。</li>
        @endforelse
    </ul>

    <nav class="p-shop-list__pager" aria-label="ページネーション">
        {{ $shops->onEachSide(1)->withQueryString()->links('vendor.pagination.shops') }}
    </nav>
</section>
@endsection

