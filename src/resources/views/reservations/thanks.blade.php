{{-- resources/views/reservations/thanks.blade.php --}}
@extends('layouts.app')
@section('title','登録完了')
@section('content')
<section class="p-result">
    <h1 class="p-result__title">登録が完了しました</h1>
    <p class="p-result__lead">ログインして店舗を予約しましょう。</p>
    <div class="p-result__actions">
        <a class="c-button" href="{{ url('/login') }}">ログインへ</a>
    </div>
</section>
@endsection
