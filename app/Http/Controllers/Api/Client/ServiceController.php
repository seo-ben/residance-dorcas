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
            'id_service' => 'required|exists:services,id',
            'quantite' => 'required|integer|min:1',
            'date_service' => 'required|date|after_or_equal:today',
            'heure_service' => 'required',
            'notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $client = Client::where('id_utilisateur', $user->id)->first();

        if (!$client) {
            $client = Client::create([
                'id_utilisateur' => $user->id,
                'telephone' => '',
                'adresse' => ''
            ]);
        }

        // Vérification d'une réservation active
        $activeReservation = Reservation::where('id_client', $client->id)
            ->where('statut', 'confirmee')
            ->whereDate('date_arrivee', '<=', Carbon::today())
            ->whereDate('date_depart', '>=', Carbon::today())
            ->first();

        $dateServiceSouhaitee = Carbon::parse($request->date_service . ' ' . $request->heure_service);

        $commande = CommandeService::create([
            'id_reservation' => $activeReservation ? $activeReservation->id : null,
            'id_client' => $client->id,
            'date_commande' => now(),
            'date_service_souhaitee' => $dateServiceSouhaitee,
            'statut' => 'en_attente',
            'notes_client' => $request->notes
        ]);

        $service = Service::find($request->id_service);

        DetailCommandeService::create([
            'id_commande_service' => $commande->id,
            'id_service' => $service->id,
            'quantite' => $request->quantite,
            'prix_unitaire' => $service->prix,
            'notes' => $request->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre commande a été enregistrée avec succès.',
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
