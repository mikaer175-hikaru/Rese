{{-- resources/views/reservations/thanks.blade.php --}}
@extends('layouts.app')
@section('title','登録完了')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/result.css') }}">
@endsection

@section('content')
<section class="p-result" aria-labelledby="thanks-title">
    <h1 id="thanks-title" class="p-result__title">登録が完了しました</h1>
    <p class="p-result__lead">ログインして店舗を予約しましょう。</p>
    <div class="p-result__actions">
        <a class="c-button" href="{{ url('/login') }}">ログインへ</a>
        <a class="c-button c-button--ghost" href="{{ url('/') }}">トップへ</a>
    </div>
</section>
@endsection

