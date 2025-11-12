<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * ログインフォーム表示
     */
    public function showForm(): View
    {
        return view('auth.login');
    }

    /**
     * ログイン処理（メール認証なし版）
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember    = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // セッション固定化対策
            $request->session()->regenerate();

            // 直前の保護ページ or 既定の行き先（例：/mypage）
            return redirect()->intended(route('mypage'));
        }

        return back()
            ->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません。'])
            ->onlyInput('email');
    }

    /**
     * ログアウト処理
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
