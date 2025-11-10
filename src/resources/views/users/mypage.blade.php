{{-- resources/views/users/mypage.blade.php --}}
@extends('layouts.app')
@section('title','マイページ')

@section('content')
<section class="p-mypage">
    <h1 class="p-mypage__title">マイページ</h1>

    <div class="p-mypage__tabs">
        <input id="tab-res" type="radio" name="tab" checked>
        <label for="tab-res" class="p-mypage__tab">予約一覧</label>
        <input id="tab-fav" type="radio" name="tab">
        <label for="tab-fav" class="p-mypage__tab">お気に入り</label>

        <div class="p-mypage__panel" id="panel-res">
            @forelse($reservations as $r)
                <article class="c-resv">
                    <div class="c-resv__head">
                        <h2 class="c-resv__title">{{ $r->shop->name }}</h2>
                        <span class="c-resv__date">{{ $r->reserve_date }} {{ substr($r->reserve_time,0,5) }}</span>
                    </div>
                    <p class="c-resv__meta">{{ $r->number_of_people }}名</p>
                    <form action="{{ route('reservations.destroy',$r) }}" method="post" class="c-resv__actions">
                        @csrf @method('delete')
                        <button class="c-button c-button--danger">予約を取消</button>
                    </form>
                </article>
            @empty
                <p class="p-mypage__empty">予約はありません。</p>
            @endforelse
        </div>

        <div class="p-mypage__panel" id="panel-fav">
            <ul class="c-card-grid">
                @forelse($favorites as $shop)
                    <li class="c-card-grid__item">
                        <article class="c-card">
                            <img src="{{ $shop->image_url }}" class="c-card__media" alt="">
                            <div class="c-card__body">
                                <h3 class="c-card__title">{{ $shop->name }}</h3>
                                <p class="c-card__meta">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
                            </div>
                            <div class="c-card__actions">
                                <a class="c-button" href="{{ url('/detail/'.$shop->id) }}">詳細</a>
                                <form action="{{ route('favorites.toggle',$shop) }}" method="post">
                                    @csrf <button class="c-button c-button--ghost">お気に入り解除</button>
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
