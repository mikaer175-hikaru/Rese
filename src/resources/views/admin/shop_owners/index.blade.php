{{-- resources/views/admin/shop_owners/index.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="p-admin-owner-index">
    <header class="p-admin-owner-index__header">
        <h1 class="p-admin-owner-index__title">店舗代表者一覧</h1>
        <a class="p-admin-owner-index__create-button"
           href="{{ route('admin.shop_owners.create') }}">
            新規作成
        </a>
    </header>

    @if (session('status'))
        <p class="p-admin-owner-index__flash">{{ session('status') }}</p>
    @endif

    <table class="p-admin-owner-index__table">
        <thead class="p-admin-owner-index__head">
            <tr class="p-admin-owner-index__row">
                <th class="p-admin-owner-index__cell">ID</th>
                <th class="p-admin-owner-index__cell">店舗代表者名</th>
                <th class="p-admin-owner-index__cell">メールアドレス</th>
                <th class="p-admin-owner-index__cell">紐づく店舗</th>
            </tr>
        </thead>
        <tbody class="p-admin-owner-index__body">
            @foreach ($shopOwners as $owner)
                <tr class="p-admin-owner-index__row">
                    <td class="p-admin-owner-index__cell">{{ $owner->id }}</td>
                    <td class="p-admin-owner-index__cell">{{ $owner->name }}</td>
                    <td class="p-admin-owner-index__cell">{{ $owner->email }}</td>
                    <td class="p-admin-owner-index__cell">
                        {{ optional($owner->shop)->name ?? '未設定' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="p-admin-owner-index__pagination">
        {{ $shopOwners->links() }}
    </div>
</main>
@endsection
