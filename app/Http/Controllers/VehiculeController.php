<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use App\Models\LocationVehicule;
use App\Models\Reservation;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VehiculeController extends Controller
{
    public function index()
    {
        $vehicules = Vehicule::with('primaryImage')
            ->where('statut', 'disponible')
            ->latest()
            ->get();
            
        return view('vehicules.index', compact('vehicules'));
    }

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
        
        return view('vehicules.show', compact('vehicule', 'activeReservations'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'id_vehicule' => 'required|exists:vehicules,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'id_reservation' => 'nullable|exists:reservations,id',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Veuillez vous connecter pour louer un véhicule.');
        }

        $user = Auth::user();
        $client = Client::where('id_utilisateur', $user->id)->first();

        if (!$client) {
            $client = Client::create([
                'id_utilisateur' => $user->id,
                'telephone' => '',
                'adresse' => ''
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

        // Optionnel: On pourrait marquer le véhicule comme loué ou indisponible temporairement
        // $vehicule->update(['statut' => 'loue']);

        return redirect()->route('vehicules.confirmation', $location->id)
            ->with('success', 'Votre demande de location a été enregistrée.');
    }

    public function confirmation($id)
    {
        $location = LocationVehicule::with(['vehicule', 'vehicule.primaryImage'])->findOrFail($id);
        return view('vehicules.confirmation', compact('location'));
    }
}
