<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::verifyEmailView(fn () => view('auth.verify'));

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->string('email'))->first();

            if ($user && Hash::check($request->string('password'), $user->password)) {
                return $user;
            }
            return null;
        });

        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);
    }
}


