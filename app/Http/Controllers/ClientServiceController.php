<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\CommandeService;
use App\Models\DetailCommandeService;
use App\Models\Reservation;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClientServiceController extends Controller
{
    /**
     * Affiche la liste des services disponibles.
     */
    public function index()
    {
        $services = Service::where('statut', 'actif')
            ->get();

        return view('services.index', compact('services'));
    }

    /**
     * Enregistre une commande de service.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Veuillez vous connecter pour commander.'], 401);
        }

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
            // Création automatique d'un profil client si manquant
            $client = Client::create([
                'id_utilisateur' => $user->id,
                'telephone' => '',
                'adresse' => ''
            ]);
        }

        // Vérification d'une réservation active (séjour en cours)
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
            'message' => 'Votre commande a été enregistrée avec succès.',
            'commande' => $commande
        ]);
    }

    /**
     * Affiche les détails d'un service spécifique.
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        
        return view('services.show', compact('service'));
    }
}
