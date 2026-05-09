<?php

namespace App\Services;

use App\Models\TokenReinitialisation;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CustomTokenRepository implements TokenRepositoryInterface
{
    public function create($user)
    {
        $token = Str::random(60);

        TokenReinitialisation::create([
            'id_utilisateur' => $user->id,
            'token' => $this->hashToken($token),
            'date_creation' => now(),
            'date_expiration' => now()->addMinutes(60),
            'utilisé' => false,
        ]);

        return $token;
    }

    public function exists($user, $token)
    {
        $record = TokenReinitialisation::where('id_utilisateur', $user->id)
            ->where('utilisé', false)
            ->where('date_expiration', '>', now())
            ->first();

        return $record && $this->checkToken($record, $token);
    }

    public function recentlyCreatedToken($user)
    {
        $record = TokenReinitialisation::where('id_utilisateur', $user->id)
            ->where('date_creation', '>', now()->subMinutes(1))
            ->first();

        return $record !== null;
    }

    public function delete($user)
    {
        TokenReinitialisation::where('id_utilisateur', $user->id)->delete();
    }

    public function deleteExpired()
    {
        TokenReinitialisation::where('date_expiration', '<', now())->delete();
    }

    protected function hashToken($token)
    {
        return hash('sha256', $token);
    }

    protected function checkToken($record, $token)
    {
        return hash_equals($record->token, $this->hashToken($token));
    }
}