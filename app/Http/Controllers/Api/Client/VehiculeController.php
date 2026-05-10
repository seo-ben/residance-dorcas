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
        
        // Trouver tous les IDs de clients liés à cet utilisateur
        // Cela gère les doublons potentiels dans la table 'clients'
        $clientIds = Client::where('id_utilisateur', $user->id)->pluck('id');

        if ($clientIds->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $locations = LocationVehicule::with(['vehicule.primaryImage', 'reservation'])
            ->whereIn('id_client', $clientIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $locations
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
