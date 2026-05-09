<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class LoginWithGoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            $finduser = User::where('google_id', $user->id)
                            ->orWhere('email', $user->email)
                            ->first();

            if($finduser) {
                // If user exists but doesn't have google_id, update it
                if (!$finduser->google_id) {
                    $finduser->update([
                        'google_id' => $user->id,
                        'avatar' => $user->avatar,
                    ]);
                }
                
                Auth::login($finduser);
                
                if ($finduser->type_utilisateur === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect()->intended('/');
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'avatar' => $user->avatar,
                    'password' => encrypt('my-google-auth-pass-'.rand(1,10000)),
                    'type_utilisateur' => 'client', // Default to client
                    'statut' => 'actif',
                    'date_creation' => now(),
                ]);

                Auth::login($newUser);
                
                if ($newUser->type_utilisateur === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect()->intended('/');
            }

        } catch (Exception $e) {
            return redirect('login')->with('error', 'Une erreur est survenue lors de la connexion avec Google.');
        }
    }
}
