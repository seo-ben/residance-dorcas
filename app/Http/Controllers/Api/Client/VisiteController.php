<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DemandeVisite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VisiteController extends Controller
{
    /**
     * @group Client - Visites
     * Liste des demandes de visite du client
     */
    public function index()
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

        $visites = DemandeVisite::with('chambre.propriete')
            ->whereIn('id_client', $allClientIds)
            ->orderBy('date_visite_souhaitee', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'debug_search_info' => [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'client_ids_found' => $allClientIds,
                'search_method' => 'ID + Email Fallback',
                'count' => $visites->count()
            ],
            'data' => $visites
        ]);
    }

    /**
     * @group Client - Visites
     * Détails d'une demande de visite
     */
    public function show($id)
    {
        $client = Client::where('id_utilisateur', Auth::id())->firstOrFail();

        $demande = DemandeVisite::with([
            'chambre.medias',
            'chambre.typeChambre',
            'chambre.propriete',
            'reservation.paiements'
        ])
        ->where('id_client', $client->id)
        ->findOrFail($id);
        
        $estPayee = false;
        if ($demande->reservation) {
            $estPayee = $demande->reservation->paiements()
                ->where('statut', 'valide')
                ->exists();
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'demande' => $demande,
                'est_payee' => $estPayee
            ]
        ]);
    }

    /**
     * @group Client - Visites
     * Créer une demande de visite
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_chambre' => 'required|exists:appartement,id',
            'date_visite' => 'required|date|after_or_equal:today',
            'heure_visite' => 'required|date_format:H:i',
            'message' => 'nullable|string'
        ]);

        $user = Auth::user();
        $client = $user->client;

        if (!$client) {
            $client = Client::create([
                'id_utilisateur' => $user->id,
                'points_fidelite' => 0
            ]);
        }

        $dateVisiteSouhaitee = Carbon::parse($request->date_visite . ' ' . $request->heure_visite);

        $demande = DemandeVisite::create([
            'id_client' => $client->id,
            'id_chambre' => $request->id_chambre,
            'date_visite_souhaitee' => $dateVisiteSouhaitee,
            'message' => $request->message,
            'statut' => 'en_attente'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre demande de visite a été enregistrée avec succès.',
            'data' => $demande->load('chambre.propriete')
        ]);
    }
}
