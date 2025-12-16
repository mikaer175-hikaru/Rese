{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/register.css') }}">
@endsection

@section('content')
<main class="p-register" aria-labelledby="register-title">
    <section class="p-register__card">
        <h1 id="register-title" class="p-register__title">会員登録</h1>

        <form class="p-register__form" action="{{ route('register') }}" method="post">
            @csrf

            <div class="p-register__field">
                <label for="name" class="p-register__label">お名前</label>
                <input id="name" class="p-register__input" type="text" name="name" value="{{ old('name') }}">
                @error('name')
                    <p class="p-register__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-register__field">
                <label for="email" class="p-register__label">メールアドレス</label>
                <input id="email" class="p-register__input" type="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <p class="p-register__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-register__field">
                <label for="password" class="p-register__label">パスワード</label>
                <input id="password" class="p-register__input" type="password" name="password">
                @error('password')
                    <p class="p-register__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-register__field">
                <label for="password_confirmation" class="p-register__label">確認用パスワード</label>
                <input id="password_confirmation" class="p-register__input" type="password" name="password_confirmation">
                @error('password_confirmation')
                    <p class="p-register__error">{{ $message }}</p>
                @enderror
            </div>

            <button class="p-register__button" type="submit">登録する</button>
        </form>

        <p class="p-register__helper">
            すでにアカウントをお持ちの方は
            <a class="p-register__link" href="{{ route('login') }}">ログインはこちら</a>
        </p>
    </section>
</main>
@endsection
