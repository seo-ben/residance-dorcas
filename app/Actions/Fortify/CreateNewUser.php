<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\Client;
use App\Models\Administrateur; // Added Administrateur model import
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'indicatif' => ['required', 'string', 'max:5'],
            'telephone' => ['required', 'string', 'max:20'],
            'type_utilisateur' => ['sometimes', 'string', 'in:admin,client'], // Added validation for user type
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            // Set default user type to client if not specified
            $userType = $input['type_utilisateur'] ?? 'client';
            
            // Combiner l'indicatif et le téléphone
            $telephoneComplet = $input['indicatif'] . ' ' . $input['telephone'];

            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'telephone' => $telephoneComplet,
                'type_utilisateur' => $userType,
            ]);
            
            // Create appropriate record based on user type
            if ($userType === 'client') {
                Client::create([
                    'id_utilisateur' => $user->id,
                    'points_fidelite' => 0,
                ]);
            } else if ($userType === 'admin') {
                Administrateur::create([
                    'id_utilisateur' => $user->id,
                    'fonction' => $input['fonction'] ?? 'Gestionnaire',
                    'niveau_acces' => $input['niveau_acces'] ?? 'standard',
                ]);
            }
            
            $this->createTeam($user);
            
            return $user;
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}