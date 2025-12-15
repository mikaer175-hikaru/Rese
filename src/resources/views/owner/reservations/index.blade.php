{{-- resources/views/owner/reservations/index.blade.php --}}
@extends('layouts.app')

@section('title', '予約一覧 | 店舗代表者')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/owner-reservations-index.css') }}">
@endsection

@section('content')
<main class="p-owner-reservation" aria-labelledby="owner-reservation-title">
    <header class="p-owner-reservation__header">
        <h1 id="owner-reservation-title" class="p-owner-reservation__title">
            予約一覧
        </h1>
    </header>

    {{-- 絞り込みフォーム --}}
    <section class="p-owner-reservation__filter" aria-label="予約の絞り込み">
        <form class="p-owner-reservation__filter-form"
              action="{{ route('owner.reservations.index') }}"
              method="get">
            <div class="p-owner-reservation__filter-row">
                <div class="p-owner-reservation__filter-group">
                    <label for="period" class="p-owner-reservation__label">期間</label>
                    <select name="period" id="period" class="p-owner-reservation__select">
                        <option value="all"   {{ $period === 'all' ? 'selected' : '' }}>すべて</option>
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>今日</option>
                        <option value="week"  {{ $period === 'week' ? 'selected' : '' }}>今週</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>今月</option>
                    </select>
                </div>

                <div class="p-owner-reservation__filter-group">
                    <label for="shop_id" class="p-owner-reservation__label">店舗</label>
                    <select name="shop_id" id="shop_id" class="p-owner-reservation__select">
                        <option value="">すべて</option>
                        @foreach ($shops as $shop)
                            <option value="{{ $shop->id }}"
                                {{ (string) $shopId === (string) $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="p-owner-reservation__filter-actions">
                    <button type="submit"
                            class="p-owner-reservation__button p-owner-reservation__button--primary">
                        絞り込み
                    </button>
                </div>
            </div>
        </form>
    </section>

    {{-- 一覧テーブル --}}
    <section class="p-owner-reservation__list" aria-label="予約一覧">
        <table class="p-owner-reservation__table">
            <thead class="p-owner-reservation__thead">
                <tr class="p-owner-reservation__row p-owner-reservation__row--head">
                    <th class="p-owner-reservation__cell p-owner-reservation__cell--head">店舗</th>
                    <th class="p-owner-reservation__cell p-owner-reservation__cell--head">予約日</th>
                    <th class="p-owner-reservation__cell p-owner-reservation__cell--head">時刻</th>
                    <th class="p-owner-reservation__cell p-owner-reservation__cell--head">人数</th>
                    <th class="p-owner-reservation__cell p-owner-reservation__cell--head">利用者</th>
                    <th class="p-owner-reservation__cell p-owner-reservation__cell--head">予約作成日時</th>
                    <th class="p-owner-reservation__cell p-owner-reservation__cell--head">ステータス</th>
                </tr>
            </thead>
            <tbody class="p-owner-reservation__tbody">
                @forelse ($reservations as $reservation)
                    <tr class="p-owner-reservation__row">
                        <td class="p-owner-reservation__cell">
                            {{ $reservation->shop->name }}
                        </td>
                        <td class="p-owner-reservation__cell">
                            {{ \Carbon\Carbon::parse($reservation->reserve_date)->format('Y-m-d') }}
                        </td>
                        <td class="p-owner-reservation__cell">
                            {{ \Carbon\Carbon::parse($reservation->reserve_time)->format('H:i') }}
                        </td>
                        <td class="p-owner-reservation__cell">
                            {{ $reservation->number_of_people }} 名
                        </td>
                        <td class="p-owner-reservation__cell">
                            @if ($reservation->user && $reservation->user->name)
                                {{ $reservation->user->name }}
                            @elseif ($reservation->user && $reservation->user->email)
                                {{ $reservation->user->email }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-owner-reservation__cell">
                            {{ $reservation->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="p-owner-reservation__cell">
                            {{ $reservation->status_label ?? '通常' }}
                        </td>
                    </tr>
                @empty
                    <tr class="p-owner-reservation__row p-owner-reservation__row--empty">
                        <td class="p-owner-reservation__cell" colspan="7">
                            条件に一致する予約はありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-owner-reservation__pagination">
            {{ $reservations->links() }}
        </div>
    </section>
</main>
@endsection
