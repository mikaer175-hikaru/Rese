{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
@section('title','ログイン')
@section('content')
<section class="p-auth">
    <h1 class="p-auth__title">ログイン</h1>
    <form method="post" action="{{ route('login') }}" class="c-form">
        @csrf
        <div class="c-form__field">
            <label class="c-form__label" for="email">メールアドレス</label>
            <input id="email" type="email" name="email" class="c-input" value="{{ old('email') }}" required>
            @error('email')<p class="c-form__error">{{ $message }}</p>@enderror
        </div>
        <div class="c-form__field">
            <label class="c-form__label" for="password">パスワード</label>
            <input id="password" type="password" name="password" class="c-input" required>
            @error('password')<p class="c-form__error">{{ $message }}</p>@enderror
        </div>
        <button class="c-button c-button--primary">ログイン</button>
    </form>
</section>
@endsection
