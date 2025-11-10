{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title','会員登録')
@section('content')
<section class="p-auth">
    <h1 class="p-auth__title">会員登録</h1>
    <form method="post" action="{{ route('register') }}" class="c-form">
        @csrf
        <div class="c-form__field">
            <label class="c-form__label" for="name">ユーザーネーム</label>
            <input id="name" name="name" class="c-input" value="{{ old('name') }}" maxlength="191" required>
            @error('name')<p class="c-form__error">{{ $message }}</p>@enderror
        </div>
        <div class="c-form__field">
            <label class="c-form__label" for="email">メールアドレス</label>
            <input id="email" type="email" name="email" class="c-input" value="{{ old('email') }}" maxlength="191" required>
            @error('email')<p class="c-form__error">{{ $message }}</p>@enderror
        </div>
        <div class="c-form__field">
            <label class="c-form__label" for="password">パスワード</label>
            <input id="password" type="password" name="password" class="c-input" minlength="8" maxlength="191" required>
            @error('password')<p class="c-form__error">{{ $message }}</p>@enderror
        </div>
        <button class="c-button c-button--primary">登録する</button>
    </form>
</section>
@endsection
