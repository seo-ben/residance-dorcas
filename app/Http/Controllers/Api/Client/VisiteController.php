<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DemandeVisite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisiteController extends Controller
{
    /**
     * @group Client - Visites
     * Liste des demandes de visite du client
     */
    public function index()
    {
        $client = Client::where('id_utilisateur', Auth::id())->firstOrFail();
        
        $demandes = DemandeVisite::with([
            'chambre.propriete', 
            'chambre.typeChambre',
            'reservation.paiements'
        ])
        ->where('id_client', $client->id)
        ->orderBy('date_demande', 'desc')
        ->get();
            
        return response()->json([
            'success' => true,
            'data' => $demandes
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
}
