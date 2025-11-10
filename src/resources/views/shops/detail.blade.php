{{-- resources/views/shops/detail.blade.php --}}
@extends('layouts.app')
@section('title', $shop->name . ' | 店舗詳細')

@section('content')
<section class="p-shop-detail">
    <div class="p-shop-detail__head">
        <img class="p-shop-detail__image" src="{{ $shop->image_url }}" alt="">
        <div class="p-shop-detail__summary">
            <h1 class="p-shop-detail__title">{{ $shop->name }}</h1>
            <p class="p-shop-detail__meta">#{{ $shop->area->name }} #{{ $shop->genre->name }}</p>
            <p class="p-shop-detail__desc">{{ $shop->description }}</p>
        </div>
    </div>

    <div class="p-shop-detail__body">
        <div class="p-shop-detail__col p-shop-detail__col--main">
            <h2 class="p-shop-detail__subtitle">店舗情報</h2>
            {{-- 任意の追加情報 --}}
        </div>

        <aside class="p-shop-detail__col p-shop-detail__col--form">
            <h2 class="p-shop-detail__subtitle">予約する</h2>
            @auth
            <form action="{{ route('reservations.store',$shop) }}" method="post" class="c-form">
                @csrf
                <div class="c-form__field">
                    <label class="c-form__label" for="reserve_date">予約日</label>
                    <input id="reserve_date" type="date" name="reserve_date" class="c-input" value="{{ old('reserve_date') }}">
                    @error('reserve_date')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>
                <div class="c-form__field">
                    <label class="c-form__label" for="reserve_time">予約時間</label>
                    <input id="reserve_time" type="time" name="reserve_time" class="c-input" value="{{ old('reserve_time') }}">
                    @error('reserve_time')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>
                <div class="c-form__field">
                    <label class="c-form__label" for="number_of_people">人数</label>
                    <input id="number_of_people" type="number" min="1" max="20" name="number_of_people" class="c-input" value="{{ old('number_of_people',1) }}">
                    @error('number_of_people')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>
                <div class="c-form__field">
                    <label class="c-form__label" for="note">メモ（任意）</label>
                    <textarea id="note" name="note" class="c-textarea" rows="3" maxlength="255">{{ old('note') }}</textarea>
                    @error('note')<p class="c-form__error">{{ $message }}</p>@enderror
                </div>
                <button class="c-button c-button--primary">予約する</button>
            </form>
            @else
                <p class="p-shop-detail__signin">予約には<a href="{{ url('/login') }}">ログイン</a>が必要です。</p>
            @endauth
        </aside>
    </div>
</section>
@endsection
