<?php

namespace App\Providers;

use App\Models\TokenReinitialisation;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class PasswordResetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Personnaliser la notification de réinitialisation de mot de passe
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return url(route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]));
        });
    }

    public function register()
    {
        // Remplacer le broker de réinitialisation de mot de passe
        $this->app->bind('auth.password.broker', function ($app) {
            return new CustomPasswordBroker(
                $app['auth.password.tokens'],
                $app['auth']->createUserProvider(Config::get('auth.providers.users.driver'))
            );
        });

        // Remplacer le repository de tokens
        $this->app->bind('auth.password.tokens', function ($app) {
            return new CustomTokenRepository();
        });
    }
}