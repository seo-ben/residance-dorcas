<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\CommandeService;
use App\Models\DetailCommandeService;
use App\Models\Reservation;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceController extends Controller
{
    /**
     * @group Client - Services
     * Liste des commandes de services du client
     */
    public function indexCommandes()
    {
        $user = Auth::user();
        
        // 1. Récupérer les IDs de clients liés par id_utilisateur
        $clientIds = Client::where('id_utilisateur', $user->id)->pluck('id')->toArray();

        // 2. Fallback: Chercher par email si nécessaire
        $clientByEmail = Client::whereHas('user', function($q) use ($user) {
            $q->where('email', $user->email);
        })->pluck('id')->toArray();
        
        $allClientIds = array_unique(array_merge($clientIds, $clientByEmail));

        if (empty($allClientIds)) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $commandes = CommandeService::with(['details.service', 'reservation'])
            ->whereIn('id_client', $allClientIds)
            ->orderBy('date_commande', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $commandes
        ]);
    }

    /**
     * @group Client - Services
     * Liste des services disponibles
     */
    public function index()
    {
        $services = Service::where('statut', 'actif')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * @group Client - Services
     * Commander un service
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_service' => 'required|exists:services,id,statut,actif',
            'quantite' => 'required|integer|min:1',
            'date_service' => 'required|date|after_or_equal:today',
            'heure_service' => 'required|date_format:H:i',
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

        // Vérification d'une réservation active
        $activeReservation = Reservation::where('id_client', $client->id)
            ->where('statut', 'confirmee')
            ->whereDate('date_arrivee', '<=', Carbon::today())
            ->whereDate('date_depart', '>=', Carbon::today())
            ->first();

        $dateServiceSouhaitee = Carbon::parse($request->date_service . ' ' . $request->heure_service);

        $service = Service::find($request->id_service);

        $commande = CommandeService::create([
            'id_reservation' => $activeReservation ? $activeReservation->id : null,
            'id_client' => $client->id,
            'date_commande' => now(),
            'date_service_souhaitee' => $dateServiceSouhaitee,
            'statut' => 'en_attente',
            'notes_client' => $request->notes
        ]);

        $commande->details()->create([
            'id_service' => $service->id,
            'quantite' => $request->quantite,
            'prix_unitaire' => $service->prix,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre commande de service a été enregistrée.',
            'data' => $commande->load('details.service')
        ]);
    }

    /**
     * @group Client - Services
     * Détails d'un service
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }
}
