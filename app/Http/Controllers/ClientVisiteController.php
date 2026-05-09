<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DemandeVisite;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientVisiteController extends Controller
{
    public function index()
    {
        $client = Client::where('id_utilisateur', Auth::id())->firstOrFail();
        
        
        // Récupérer les demandes de visite selon les critères spécifiés
        $demandes = DemandeVisite::with([
            'chambre.propriete', 
            'chambre.typeChambre',
            'reservation.paiements'
        ])
        ->where('id_client', $client->id)
        ->where(function($query) {
            $query->whereDoesntHave('reservation')
                  ->orWhereHas('reservation', function($q) {
                      $q->where('statut', 'annulee')
                        ->orWhereDoesntHave('paiements')
                        ->orWhereHas('paiements', function($p) {
                            $p->whereIn('statut', ['en_attente', 'refuse', 'rembourse']);
                        });
                  });
        })
        ->orderBy('date_demande', 'desc')
        ->get();
            
        $reservations = $client->reservations()
            ->with(['details.chambre.propriete'])
            ->orderBy('date_creation', 'desc')
            ->get();
            
        return view('mes-visites.index', compact('demandes', 'reservations'));
    }

    public function show($id)
    {
        // Récupérer la demande de visite avec toutes les relations nécessaires
        $demande = DemandeVisite::with([
            'chambre.medias',
            'chambre.typeChambre',
            'chambre.propriete',
            'reservation.paiements'
        ])
        ->where('id_client', auth()->id())
        ->findOrFail($id);
        
        // Récupérer la réservation associée avec ses détails
        $reservation = null;
        $estPayee = false;
        
        if ($demande->reservation) {
            $reservation = $demande->reservation;
            // Vérifier si la réservation est payée
            $estPayee = $reservation->paiements()
                ->where('statut', 'valide')
                ->exists();
        }
        
        return view('mes-visites.show', compact('demande', 'reservation', 'estPayee'));
    }
}