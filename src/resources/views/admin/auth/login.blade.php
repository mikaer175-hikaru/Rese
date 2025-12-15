{{-- resources/views/admin/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="p-admin-login">
    <section class="p-admin-login__card">
        <h1 class="p-admin-login__title">管理者ログイン</h1>

        <form class="p-admin-login__form" action="{{ route('admin.login.submit') }}" method="post">
            @csrf

            <div class="p-admin-login__field">
                <label for="email" class="p-admin-login__label">メールアドレス</label>
                <input id="email" class="p-admin-login__input" type="email" name="email"
                    value="{{ old('email') }}">
                @error('email')
                    <p class="p-admin-login__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-admin-login__field">
                <label for="password" class="p-admin-login__label">パスワード</label>
                <input id="password" class="p-admin-login__input" type="password" name="password">
                @error('password')
                    <p class="p-admin-login__error">{{ $message }}</p>
                @enderror
            </div>

            <button class="p-admin-login__button" type="submit">ログイン</button>
        </form>
    </section>
</main>
@endsection
