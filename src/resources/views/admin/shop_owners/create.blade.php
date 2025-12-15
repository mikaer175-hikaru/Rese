{{-- resources/views/admin/shop_owners/create.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="p-admin-owner-create">
    <section class="p-admin-owner-create__card">
        <h1 class="p-admin-owner-create__title">店舗代表者アカウント作成</h1>

        <form class="p-admin-owner-create__form"
              action="{{ route('admin.shop_owners.store') }}"
              method="post">
            @csrf

            {{-- お名前 --}}
            <div class="p-admin-owner-create__field">
                <label for="name" class="p-admin-owner-create__label">お名前</label>
                <input id="name" class="p-admin-owner-create__input"
                       type="text" name="name" value="{{ old('name') }}">
                @error('name')
                    <p class="p-admin-owner-create__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- メールアドレス --}}
            <div class="p-admin-owner-create__field">
                <label for="email" class="p-admin-owner-create__label">メールアドレス</label>
                <input id="email" class="p-admin-owner-create__input"
                       type="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <p class="p-admin-owner-create__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- パスワード --}}
            <div class="p-admin-owner-create__field">
                <label for="password" class="p-admin-owner-create__label">パスワード</label>
                <input id="password" class="p-admin-owner-create__input"
                       type="password" name="password">
                @error('password')
                    <p class="p-admin-owner-create__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 確認用パスワード --}}
            <div class="p-admin-owner-create__field">
                <label for="password_confirmation" class="p-admin-owner-create__label">
                    確認用パスワード
                </label>
                <input id="password_confirmation" class="p-admin-owner-create__input"
                       type="password" name="password_confirmation">
                @error('password_confirmation')
                    <p class="p-admin-owner-create__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 紐づく店舗 --}}
            <div class="p-admin-owner-create__field">
                <label for="shop_id" class="p-admin-owner-create__label">紐づく店舗</label>
                <select id="shop_id" class="p-admin-owner-create__select" name="shop_id">
                    <option value="">選択してください</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}"
                            @selected(old('shop_id') == $shop->id)>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
                @error('shop_id')
                    <p class="p-admin-owner-create__error">{{ $message }}</p>
                @enderror
            </div>

            <button class="p-admin-owner-create__button" type="submit">
                登録する
            </button>
        </form>
    </section>
</main>
@endsection
