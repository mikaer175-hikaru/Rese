{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title','Rese')</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    @yield('css')
</head>
<body class="l-body">
    <header class="c-header">
        <div class="c-header__inner">
            <a href="{{ url('/') }}" class="c-header__logo">Rese</a>
            <nav class="c-header__nav" aria-label="グローバルナビゲーション">
                <a href="{{ route('shops.index') }}" class="c-header__link">店舗一覧</a>

                @auth
                    {{-- 全ログインユーザー共通メニュー --}}
                    @php /** @var \App\Models\User $user */ $user = auth()->user(); @endphp

                    <a href="{{ route('mypage') }}" class="c-header__link">マイページ</a>

                    @if ($user->isOwner())
                        <a href="{{ route('owner.dashboard') }}" class="c-header__link">店舗管理</a>
                    @elseif ($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="c-header__link">管理者メニュー</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="c-header__form">
                        @csrf
                        <button type="submit" class="c-header__button">ログアウト</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="c-header__link">ログイン</a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="l-main">
        @yield('content')
    </main>

    <footer class="c-footer">
        <small class="c-footer__copy">© Rese</small>
    </footer>
</body>
</html>
