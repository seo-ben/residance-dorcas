<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Vehicule;
use App\Models\LocationVehicule;
use App\Models\Reservation;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VehiculeController extends Controller
{
    /**
     * @group Client - Véhicules
     * Liste des locations de véhicules du client
     */
    public function indexLocations()
    {
        $user = Auth::user();
        
        // 1. Récupérer les IDs de clients liés par id_utilisateur
        $clientIds = Client::where('id_utilisateur', $user->id)->pluck('id')->toArray();

        // 2. Fallback: Chercher par email si nécessaire
        $clientByEmail = Client::whereHas('user', function($q) use ($user) {
            $q->where('email', $user->email);
        })->get();
        
        $clientIdsByEmail = $clientByEmail->pluck('id')->toArray();
        
        // Fusionner et dédoublonner les IDs
        $allClientIds = array_unique(array_merge($clientIds, $clientIdsByEmail));

        if (empty($allClientIds)) {
            return response()->json([
                'success' => true,
                'debug_search_info' => [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'status' => 'No client profile found'
                ],
                'data' => [],
                'message' => 'Aucun profil client trouvé.'
            ]);
        }

        // Récupérer les détails des profils clients trouvés pour le debug
        $debugClients = Client::whereIn('id', $allClientIds)->get()->map(function($c) {
            return [
                'client_id' => $c->id,
                'linked_user_id' => $c->id_utilisateur,
                'has_user_relation' => $c->user ? 'YES' : 'NO'
            ];
        });

        // 3. Récupérer les locations avec toutes les relations nécessaires
        // On inclut vehicule.images pour s'assurer d'avoir au moins une image si primaryImage est nulle
        $locations = LocationVehicule::with([
            'vehicule.primaryImage', 
            'vehicule.images',
            'reservation',
            'client.user'
        ])
        ->whereIn('id_client', $allClientIds)
        ->orderBy('created_at', 'desc')
        ->get();

        // 4. Transformation pour s'assurer que chaque véhicule a une image par défaut si nécessaire
        $locations->transform(function ($location) {
            if ($location->vehicule) {
                if (!$location->vehicule->primaryImage && $location->vehicule->images->isNotEmpty()) {
                    $location->vehicule->setRelation('primaryImage', $location->vehicule->images->first());
                }
            }
            return $location;
        });

        return response()->json([
            'success' => true,
            'debug_search_info' => [
                'authenticated_user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
                'client_profiles_found' => $debugClients,
                'search_method' => 'ID + Email Fallback',
                'total_locations_found' => $locations->count()
            ],
            'data' => $locations,
            'locations' => $locations // Doubler la clé au cas où l'app attend 'locations'
        ]);
    }

    /**
     * @group Client - Véhicules
     * Liste des véhicules disponibles
     */
    public function index()
    {
        $vehicules = Vehicule::with('primaryImage')
            ->where('statut', 'disponible')
            ->latest()
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $vehicules
        ]);
    }

    /**
     * @group Client - Véhicules
     * Détails d'un véhicule
     */
    public function show($id)
    {
        $vehicule = Vehicule::with('images')->findOrFail($id);
        
        $activeReservations = [];
        if (Auth::check()) {
            $user = Auth::user();
            $client = Client::where('id_utilisateur', $user->id)->first();
            
            if ($client) {
                $activeReservations = Reservation::where('id_client', $client->id)
                    ->whereIn('statut', ['confirmee', 'en_attente_paiement'])
                    ->where('date_depart', '>=', now())
                    ->get();
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'vehicule' => $vehicule,
                'active_reservations' => $activeReservations
            ]
        ]);
    }

    /**
     * @group Client - Véhicules
     * Réserver un véhicule
     */
    public function book(Request $request)
    {
        $request->validate([
            'id_vehicule' => 'required|exists:vehicules,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'id_reservation' => 'nullable|exists:reservations,id',
            'notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $client = $user->client;

        if (!$client) {
            $client = Client::create([
                'id_utilisateur' => $user->id,
                'points_fidelite' => 0
            ]);
        }

        $vehicule = Vehicule::findOrFail($request->id_vehicule);
        
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = Carbon::parse($request->date_fin);
        $jours = $dateDebut->diffInDays($dateFin);
        if ($jours == 0) $jours = 1;
        
        $prixTotal = $vehicule->prix_journalier * $jours;

        $location = LocationVehicule::create([
            'id_vehicule' => $vehicule->id,
            'id_client' => $client->id,
            'id_reservation' => $request->id_reservation,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'prix_total' => $prixTotal,
            'statut' => 'en_attente',
            'statut_paiement' => 'non_paye',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre demande de location a été enregistrée.',
            'data' => $location->load(['vehicule.primaryImage'])
        ]);
    }
}
