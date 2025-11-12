{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title','Rese')</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages.css') }}">
    @yield('css')
</head>
<body class="l-body">
    <header class="c-header">
        <div class="c-header__inner">
            <a href="{{ url('/') }}" class="c-header__logo">Rese</a>
            <nav class="c-header__nav">
                @auth
                    <a href="{{ url('/mypage') }}" class="c-header__link">マイページ</a>
                    <form action="{{ route('logout') }}" method="post" class="c-header__logout">
                        @csrf <button class="c-button c-button--ghost">ログアウト</button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="c-header__link">ログイン</a>
                    <a href="{{ url('/register') }}" class="c-header__link">会員登録</a>
                @endauth
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
