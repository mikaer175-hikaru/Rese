{{-- resources/views/shops/index.blade.php --}}
@extends('layouts.app')
@section('title','店舗一覧')

@section('content')
<section class="p-shop-list" aria-labelledby="shop-list-title">
    <h1 id="shop-list-title" class="p-shop-list__title">店舗を探す</h1>

    <form action="{{ url('/') }}" method="get" class="p-shop-list__filter c-filter">
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
                <label class="c-filter__label" for="keyword">店名</label>
                <input id="keyword" name="keyword" class="c-input" value="{{ request('keyword') }}" placeholder="店名で検索">
            </div>
            <div class="c-filter__actions">
                <button class="c-button">検索</button>
                <a href="{{ url('/') }}" class="c-button c-button--ghost">リセット</a>
            </div>
        </div>
    </form>

    <ul class="p-shop-list__grid c-card-grid">
        @forelse($shops as $shop)
            <li class="c-card-grid__item">
                <article class="c-card">
                    <img src="{{ $shop->image_url }}" alt="" class="c-card__media">
                    <div class="c-card__body">
                        <h2 class="c-card__title">{{ $shop->name }}</h2>
                        <p class="c-card__meta">#{{ $shop->area->name }}　#{{ $shop->genre->name }}</p>
                        <p class="c-card__text">{{ Str::limit($shop->description, 60) }}</p>
                    </div>
                    <div class="c-card__actions">
                        <a class="c-button" href="{{ url('/detail/'.$shop->id) }}">詳しく見る</a>
                        @auth
                            <form action="{{ route('favorites.toggle',$shop) }}" method="post">
                                @csrf
                                <button class="c-button c-button--ghost">
                                    {{ $shop->is_favorited ? '★ お気に入り' : '☆ お気に入り' }}
                                </button>
                            </form>
                        @endauth
                    </div>
                </article>
            </li>
        @empty
            <li class="p-shop-list__empty">該当する店舗はありません。</li>
        @endforelse
    </ul>

    <div class="p-shop-list__pager">
        {{ $shops->withQueryString()->links() }}
    </div>
</section>
@endsection
