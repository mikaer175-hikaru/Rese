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

        {{-- 予約一覧（遷移なしで変更＆QR表示） --}}
        <div class="p-mypage__panel" role="tabpanel" aria-labelledby="tab-res">
            @forelse ($reservations as $r)
            <article id="resv-{{ $r->id }}" class="c-resv" aria-labelledby="resv-{{ $r->id }}-title">
                <div class="c-resv__head">
                    <h2 id="resv-{{ $r->id }}-title" class="c-resv__title">{{ $r->shop->name }}</h2>
                    <span class="c-resv__date">
                        {{ $r->reserve_date->format('Y/m/d') }}
                        {{ $r->reserve_time_hm }}
                    </span>
                </div>

                <dl class="c-resv__list">
                    <div class="c-resv__row"><dt>予約番号</dt><dd>予約{{ $r->id }}</dd></div>
                    <div class="c-resv__row"><dt>人数</dt><dd>{{ $r->number_of_people }}名</dd></div>
                    <div class="c-resv__row"><dt>備考</dt><dd>{{ $r->note ?: '—' }}</dd></div>
                </dl>

                <div class="c-resv__actions">
                    <button class="c-button c-button--ghost" type="button"
                    onclick="this.closest('.c-resv').querySelector('.c-resv__edit').open = !this.closest('.c-resv').querySelector('.c-resv__edit').open;">編集</button>

                    <button class="c-button c-button--ghost" type="button"
                    onclick="this.closest('.c-resv').querySelector('.c-resv__qr').open = !this.closest('.c-resv').querySelector('.c-resv__qr').open;">QRを表示</button>

                    <form action="{{ route('reservations.destroy', $r) }}" method="post">
                        @csrf @method('delete')
                        <button class="c-button c-button--danger" type="submit" onclick="return confirm('この予約を取り消しますか？')">予約を取消</button>
                    </form>
                </div>

                {{-- 折りたたみ：編集フォーム --}}
                <details class="c-resv__edit">
                    <summary class="c-resv__edit-summary">予約内容を変更する</summary>

                    @if ($errors->any())
                    <ul class="c-errors">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    @endif

                    <form method="post" action="{{ route('reservations.update', $r) }}" class="c-form">
                        @csrf @method('put')
                        <label class="c-field">
                            <span class="c-field__label">予約日（翌日以降）</span>
                            <input type="date" name="reserve_date"
                                value="{{ old('reserve_date', $r->reserve_date->format('Y-m-d')) }}"
                                min="{{ now()->addDay()->toDateString() }}" required>
                        </label>

                        <label class="c-field">
                            <span class="c-field__label">予約時間</span>
                            <input type="time" name="reserve_time"
                                value="{{ old('reserve_time', \Carbon\Carbon::parse($r->reserve_time)->format('H:i')) }}" required>
                        </label>

                        <label class="c-field">
                            <span class="c-field__label">人数（1〜20）</span>
                            <input type="number" name="number_of_people" min="1" max="20"
                                value="{{ old('number_of_people', $r->number_of_people) }}" required>
                        </label>

                        <label class="c-field">
                            <span class="c-field__label">備考（任意）</span>
                            <textarea name="note" rows="3" maxlength="255">{{ old('note', $r->note) }}</textarea>
                        </label>

                        <div class="c-form__actions">
                            <button class="c-button" type="submit">この内容で更新</button>
                            <button class="c-button c-button--ghost" type="button"
                            onclick="this.closest('.c-resv').querySelector('.c-resv__edit').open = false;">閉じる</button>
                        </div>
                    </form>
                </details>

                {{-- 折りたたみ：QR表示（simple-qrcode使用） --}}
                <details class="c-resv__qr">
                    <summary class="c-resv__qr-summary">QRコードを表示する</summary>
                    <div class="c-resv__qr-box" aria-label="来店時に店舗へ提示するQRコード">
                        {!! QrCode::size(180)->generate(route('qr.verify', ['token' => $r->qr_token])) !!}
                        <p class="c-resv__qr-note">店舗でQRを提示してください。</p>
                    </div>
                </details>
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
