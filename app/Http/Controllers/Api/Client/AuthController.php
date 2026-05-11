<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Authentification utilisateur et génération de token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Les identifiants sont incorrects.',
            ], 422);
        }

        if ($user->type_utilisateur !== 'client') {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé.',
            ], 403);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user->load('client'),
        ]);
    }

    /**
     * Inscription d'un nouveau client
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'indicatif' => ['required', 'string', 'max:5'],
            'telephone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $telephoneComplet = $request->indicatif . ' ' . $request->telephone;

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'telephone' => $telephoneComplet,
                    'type_utilisateur' => 'client',
                ]);

                // Créer le client avec le même ID que l'utilisateur pour plus de clarté
                Client::create([
                    'id' => $user->id,
                    'id_utilisateur' => $user->id,
                    'points_fidelite' => 0,
                ]);

                $token = $user->createToken($request->device_name ?? 'mobile_app')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user' => $user->load('client'),
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Déconnexion (révocation du token)
     */
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if (method_exists($token, 'delete')) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.'
        ]);
    }

    /**
     * Récupérer les infos de l'utilisateur connecté
     */
    /**
     * Connexion via Google (Mobile API)
     */
    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'google_id' => 'required',
            'name' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('google_id', $request->google_id)
                    ->orWhere('email', $request->email)
                    ->first();

        if ($user) {
            // Update google_id if not set
            if (!$user->google_id) {
                $user->update(['google_id' => $request->google_id]);
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'google_id' => $request->google_id,
                'password' => Hash::make(Str::random(16)),
                'type_utilisateur' => 'client',
            ]);

            Client::create([
                'id' => $user->id,
                'id_utilisateur' => $user->id,
                'points_fidelite' => 0,
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user->load('client'),
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        
        // S'assurer que le client est chargé
        if (!$user->client) {
            $client = Client::where('id_utilisateur', $user->id)->first();
            if (!$client) {
                // Fallback par email
                $client = Client::whereHas('user', function($q) use ($user) {
                    $q->where('email', $user->email);
                })->first();
            }
            
            if ($client) {
                $user->setRelation('client', $client);
            }
        }

        return response()->json([
            'success' => true,
            'user' => $user->load('client'),
        ]);
    }
}
