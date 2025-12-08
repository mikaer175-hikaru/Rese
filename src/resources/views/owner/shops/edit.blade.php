{{-- resources/views/owner/shops/edit.blade.php --}}
@extends('layouts.app')

@section('title', '店舗情報編集')

@section('content')
<main class="p-owner-shop-form" aria-labelledby="shop-form-title">
    <h1 id="shop-form-title" class="p-owner-shop-form__title">店舗情報編集</h1>

    <form action="{{ route('owner.shops.update', $shop) }}" method="POST" enctype="multipart/form-data" class="p-form">
        @csrf
        @method('PUT')
        @include('owner.shops._form')
        <button type="submit" class="c-button c-button--primary">更新する</button>
    </form>
</main>
@endsection
