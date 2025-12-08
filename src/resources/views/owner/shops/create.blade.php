{{-- resources/views/owner/shops/create.blade.php --}}
@extends('layouts.app')

@section('title', '店舗新規登録')

@section('content')
<main class="p-owner-shop-form" aria-labelledby="shop-form-title">
    <h1 id="shop-form-title" class="p-owner-shop-form__title">店舗新規登録</h1>

    <form action="{{ route('owner.shops.store') }}" method="POST" enctype="multipart/form-data" class="p-form">
        @csrf
        @include('owner.shops._form')
        <button type="submit" class="c-button c-button--primary">登録する</button>
    </form>
</main>
@endsection
