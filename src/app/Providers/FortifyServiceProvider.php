<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ログイン
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        // 新規登録
        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);
    }
}

