{{-- resources/views/reservations/done.blade.php --}}
@extends('layouts.app')
@section('title','予約完了')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/result.css') }}">
@endsection

@section('content')
<section class="p-result" aria-labelledby="result-title">
    <h1 id="result-title" class="p-result__title">予約が完了しました</h1>
    <p class="p-result__lead">ご予約ありがとうございます。詳細はマイページで確認できます。</p>
    <div class="p-result__actions">
        <a class="c-button" href="{{ url('/mypage') }}">マイページへ</a>
        <a class="c-button c-button--ghost" href="{{ url('/') }}">トップへ</a>
    </div>
</section>
@endsection
