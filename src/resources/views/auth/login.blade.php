{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
@endsection

@section('content')
<main class="p-login" aria-labelledby="login-title">
    <section class="p-login__card">
        <h1 id="login-title" class="p-login__title">ログイン</h1>

        <form class="p-login__form" action="{{ route('login.submit') }}" method="post">
            @csrf

            <div class="p-login__field">
                <label for="email" class="p-login__label">メールアドレス</label>
                <input id="email" class="p-login__input" type="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <p class="p-login__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-login__field">
                <label for="password" class="p-login__label">パスワード</label>
                <input id="password" class="p-login__input" type="password" name="password">
                @error('password')
                    <p class="p-login__error">{{ $message }}</p>
                @enderror

                @error('login')
                    <p class="p-login__error">{{ $message }}</p>
                @enderror
            </div>

            <button class="p-login__button" type="submit">ログイン</button>
        </form>

        <p class="p-login__helper">
            アカウントをお持ちでない方は
            <a class="p-login__link" href="{{ route('register') }}">会員登録はこちら</a>
        </p>
    </section>
</main>
@endsection
