{{-- resources/views/auth/verify.blade.php --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/verify.css') }}">
@endsection

@section('content')
<main class="p-verify" aria-labelledby="verify-title">
    <section class="p-verify__card">
        <h1 id="verify-title" class="p-verify__title">メール認証を完了してください</h1>

        <p class="p-verify__text">
            登録したメールアドレス宛に認証メールを送信しました。メール内のリンクをクリックして認証を完了してください。
        </p>

        @if (session('status') === 'verification-link-sent')
            <p class="p-verify__notice">認証メールを再送しました。</p>
        @endif

        <form class="p-verify__form" method="post" action="{{ route('verification.send') }}">
            @csrf
            <button class="p-verify__button" type="submit">認証メールを再送</button>
        </form>
    </section>
</main>
@endsection
