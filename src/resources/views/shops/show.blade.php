{{-- resources/views/shops/show.blade.php --}}
@extends('layouts.app')
@section('title', $shop->name . ' | 店舗詳細')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop-show.css') }}">
@endsection

@section('content')
<section class="p-shop-detail" aria-labelledby="shop-title">
    <div class="p-shop-detail__head">
        <img class="p-shop-detail__image" src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
        <div class="p-shop-detail__summary">
            <h1 id="shop-title" class="p-shop-detail__title">{{ $shop->name }}</h1>
            <p class="p-shop-detail__meta">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
            <p class="p-shop-detail__desc">{{ $shop->description }}</p>
            <p class="p-shop-detail__rating">
                評価：{{ isset($avg) && $avg !== null ? number_format($avg, 1) : '—' }}（{{ $count ?? 0 }}件）
            </p>
        </div>
    </div>

    <div class="p-shop-detail__body">
        <div class="p-shop-detail__col p-shop-detail__col--main">
            <h2 class="p-shop-detail__subtitle">店舗情報</h2>
            {{-- 任意の追加情報 --}}
        </div>

        @if(isset($latestReviews) && $latestReviews->isNotEmpty())
        <section class="p-shop-detail__reviews" aria-label="直近の口コミ">
            <h2 class="p-shop-detail__subtitle">直近の口コミ</h2>
            <ul class="p-shop-detail__review-list">
                @foreach($latestReviews as $rv)
                    <li class="p-shop-detail__review-item">
                        <div class="p-shop-detail__review-head">
                            <strong class="p-shop-detail__review-user">{{ $rv->user->name }}</strong>
                            <span class="p-shop-detail__review-rating">★ {{ $rv->rating }}</span>
                            <span class="p-shop-detail__review-date">{{ $rv->created_at->format('Y/m/d') }}</span>
                        </div>
                        @if($rv->comment)
                            <p class="p-shop-detail__review-text">{{ $rv->comment }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
        @endif

        <aside class="p-shop-detail__col p-shop-detail__col--form" aria-labelledby="reserve-title">
            <h2 id="reserve-title" class="p-shop-detail__subtitle">予約する</h2>

            @auth
            <form action="{{ route('reservations.store') }}" method="post" class="c-form" novalidate>
                @csrf
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">

                <div class="c-form__field">
                    <label class="c-form__label" for="reserve_date">予約日</label>
                    <input
                        id="reserve_date"
                        type="date"
                        name="reserve_date"
                        class="c-input"
                        value="{{ old('reserve_date') }}"
                        min="{{ \Carbon\Carbon::now('Asia/Tokyo')->addDay()->toDateString() }}"
                    >
                    @error('reserve_date')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>

                <div class="c-form__field">
                    <label class="c-form__label" for="reserve_time">予約時間</label>
                    <input
                        id="reserve_time"
                        type="time"
                        name="reserve_time"
                        class="c-input"
                        value="{{ old('reserve_time') }}"
                    >
                    @error('reserve_time')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>

                <div class="c-form__field">
                    <label class="c-form__label" for="number_of_people">人数</label>
                    <input
                        id="number_of_people"
                        type="number"
                        min="1"
                        max="20"
                        name="number_of_people"
                        class="c-input"
                        value="{{ old('number_of_people', 1) }}"
                    >
                    @error('number_of_people')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>

                <div class="c-form__field">
                    <label class="c-form__label" for="note">メモ（任意）</label>
                    <textarea id="note" name="note" class="c-textarea" rows="3" maxlength="255">{{ old('note') }}</textarea>
                    @error('note')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>

                <div class="c-form__field">
                    <span class="c-form__label">支払い方法</span>
                    <label class="c-radio"><input type="radio" name="payment_method" value="none" {{ old('payment_method','none')==='none'?'checked':'' }}> 来店時に支払い</label>
                    <label class="c-radio"><input type="radio" name="payment_method" value="card" {{ old('payment_method')==='card'?'checked':'' }}> オンライン決済（クレジットカード）</label>
                    @error('payment_method')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>

                {{-- 予約内容プレビュー --}}
                <aside class="p-shop-detail__reserve-preview" aria-label="予約内容">
                    <h3 class="p-shop-detail__reserve-preview-title">予約内容</h3>
                    <dl class="p-shop-detail__reserve-list">
                        <div class="p-shop-detail__reserve-item"><dt>Shop</dt><dd>{{ $shop->name }}</dd></div>
                        <div class="p-shop-detail__reserve-item"><dt>Date</dt><dd>{{ old('reserve_date') }}</dd></div>
                        <div class="p-shop-detail__reserve-item"><dt>Time</dt><dd>{{ old('reserve_time') }}</dd></div>
                        <div class="p-shop-detail__reserve-item"><dt>Number</dt><dd>{{ old('number_of_people', 1) }}人</dd></div>
                    </dl>
                </aside>

                <button class="c-button c-button--primary" type="submit">予約する</button>
            </form>
            @else
                <p class="p-shop-detail__signin">予約には<a href="{{ route('login') }}">ログイン</a>が必要です。</p>
            @endauth
        </aside>
    </div>

    @auth
    {{-- 今後の予約一覧（仕様：未来予約は全て表示・複数は下に追加） --}}
    @if(isset($futureReservations))
    <section class="p-shop-detail__future" aria-label="今後の予約">
        <h2 class="p-shop-detail__subtitle">今後の予約</h2>
        @forelse($futureReservations as $r)
            <p class="p-shop-detail__future-item">
                {{ $r->shop->name }} /
                Date {{ $r->reserve_date->format('Y-m-d') }} /
                Time {{ \Illuminate\Support\Str::of($r->reserve_time)->limit(5, '') }} /
                Number {{ $r->number_of_people }}人
            </p>
        @empty
            <p class="p-shop-detail__future-empty">今後の予約はありません。</p>
        @endforelse
    </section>
    @endif
    @endauth
</section>
@endsection
