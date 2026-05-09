<?php

namespace App\Services;

use Closure;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Arr;

class CustomPasswordBroker extends PasswordBroker
{
    public function __construct(CustomTokenRepository $tokens, UserProvider $users)
    {
        parent::__construct($tokens, $users);
    }

    // Vous pouvez surcharger d'autres méthodes si nécessaire
}