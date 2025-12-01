{{-- resources/views/users/mypage.blade.php --}}
@extends('layouts.app')
@section('title','マイページ')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/mypage.css') }}">
@endsection

@section('content')
<section class="p-mypage" aria-labelledby="mypage-title">
    <h1 id="mypage-title" class="p-mypage__title">{{ $user->name }}さんのマイページ</h1>

    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <p class="c-flash c-flash--success" role="status">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p class="c-flash c-flash--error" role="alert">{{ session('error') }}</p>
    @endif

    {{-- ラジオタブ（CSSで切替） --}}
    <div class="p-mypage__tabs" role="tablist" aria-label="マイページのセクション">
        <input id="tab-res" type="radio" name="tab" class="p-mypage__radio" checked>
        <label for="tab-res" class="p-mypage__tab" role="tab" aria-controls="panel-res" aria-selected="true">予約一覧</label>

        <input id="tab-fav" type="radio" name="tab" class="p-mypage__radio">
        <label for="tab-fav" class="p-mypage__tab" role="tab" aria-controls="panel-fav" aria-selected="false">お気に入り</label>

        {{-- 予約一覧 --}}
        <div class="p-mypage__panel" id="panel-res" role="tabpanel" aria-labelledby="tab-res">
            @forelse ($reservations as $r)
                <article class="c-resv" aria-labelledby="resv-{{ $r->id }}-title">
                    <div class="c-resv__head">
                        <h1 id="resv-{{ $r->id }}-title" class="c-resv__title">{{ $r->shop->name }}</h1>
                        <span class="c-resv__date">
                            {{ $r->reserve_date->format('Y/m/d') }}
                            {{ \Carbon\Carbon::parse($r->reserve_time)->format('H:i') }}
                        </span>
                    </div>

                    <dl class="c-resv__list">
                        <div class="c-resv__row"><dt>予約番号</dt><dd>予約{{ $r->id }}</dd></div>
                        <div class="c-resv__row"><dt>人数</dt><dd>{{ $r->number_of_people }}名</dd></div>
                    </dl>

                    <form action="{{ route('reservations.destroy', $r) }}" method="post" class="c-resv__actions">
                        @csrf
                        @method('delete')
                        <button class="c-button c-button--danger" type="submit"
                            aria-label="予約{{ $r->id }}を取り消す"
                            onclick="return confirm('この予約を取り消しますか？')">
                            予約を取消
                        </button>
                    </form>
                </article>
            @empty
                <p class="p-mypage__empty">明日以降の予約はありません。</p>
            @endforelse
        </div>

        {{-- お気に入り --}}
        <div class="p-mypage__panel" id="panel-fav" role="tabpanel" aria-labelledby="tab-fav">
            <ul class="c-card-grid">
                @forelse ($favoriteShops as $shop)
                    <li class="c-card-grid__item">
                        <article class="c-card" aria-labelledby="fav-{{ $shop->id }}-title">
                            <img src="{{ $shop->image_url }}" class="c-card__media" alt="">
                            <div class="c-card__body">
                                <h2 id="fav-{{ $shop->id }}-title" class="c-card__title">{{ $shop->name }}</h2>
                                <p class="c-card__meta">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
                            </div>
                            <div class="c-card__actions">
                                <a class="c-button" href="{{ url('/detail/'.$shop->id) }}">詳細</a>
                                <form action="{{ route('favorites.destroy', $shop) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button class="c-button c-button--ghost" type="submit"
                                        aria-label="{{ $shop->name }}のお気に入りを解除">
                                        お気に入り解除
                                    </button>
                                </form>
                            </div>
                        </article>
                    </li>
                @empty
                    <li class="p-mypage__empty">お気に入りはありません。</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>
@endsection
