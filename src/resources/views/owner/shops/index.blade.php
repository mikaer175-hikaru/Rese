{{-- resources/views/owner/shops/index.blade.php --}}
@extends('layouts.app')

@section('title', '店舗管理')

@section('content')
<main class="p-owner-shops" aria-labelledby="owner-shops-title">
    <div class="p-owner-shops__head">
        <h1 id="owner-shops-title" class="p-owner-shops__title">店舗一覧</h1>
        <a href="{{ route('owner.shops.create') }}" class="c-button c-button--primary">
            新規店舗を登録
        </a>
    </div>

    @if (session('status'))
        <p class="c-alert c-alert--success">{{ session('status') }}</p>
    @endif

    @if ($shops->isEmpty())
        <p class="p-owner-shops__empty">登録されている店舗はありません。</p>
    @else
        <table class="p-owner-shops__table">
            <thead>
                <tr>
                    <th>店舗名</th>
                    <th>エリア</th>
                    <th>ジャンル</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shops as $shop)
                    <tr>
                        <td>{{ $shop->name }}</td>
                        <td>{{ $shop->area->name }}</td>
                        <td>{{ $shop->genre->name }}</td>
                        <td>
                            <a href="{{ route('owner.shops.edit', $shop) }}" class="c-link">
                                編集
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $shops->links() }}
    @endif
</main>
@endsection
