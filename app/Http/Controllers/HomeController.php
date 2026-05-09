<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\TypeChambre;
use App\Models\Propriete;
use App\Models\Equipement;
use App\Models\Reservation;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index()
    {
        // Logiqe Booking.com: Afficher les offres du moment ou les plus populaires
        $chambresPopulaires = Chambre::with(['typeChambre', 'propriete', 'medias', 'equipements', 'avis'])
            ->where('statut', 'disponible')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Ajout de contexte social (Scarcity/Urgency logic)
        foreach ($chambresPopulaires as $chambre) {
            $chambre->recent_bookings = Reservation::whereHas('details', function ($q) use ($chambre) {
                $q->where('id_chambre', $chambre->id);
            })->where('created_at', '>=', now()->subDays(7))->count();
        }

        $typesChambres = TypeChambre::all();
        $proprietes = Propriete::all();

        return view('welcome', compact('chambresPopulaires', 'typesChambres', 'proprietes'));
    }

    public function search(Request $request)
    {
        $filters = $request->only([
            'date_arrivee',
            'date_depart',
            'id_type_chambre',
            'id_propriete',
            'capacite',
            'prix_min',
            'prix_max',
            'equipements',
            'sort'
        ]);

        $resultats = $this->searchService->search($filters);

        // Données pour les filtres (Sidebars)
        $typesChambres = TypeChambre::withCount('chambres')->get();
        $proprietes = Propriete::all();
        $equipements = Equipement::all();

        return view('chambres.search', compact(
            'resultats',
            'typesChambres',
            'proprietes',
            'equipements'
        ));
    }

    public function show($id)
    {
        $chambre = Chambre::with(['typeChambre', 'propriete', 'medias', 'equipements', 'avis.client'])->findOrFail($id);

        // Logique de prix dynamique pour les 7 prochains jours
        $upcomingPrices = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i);
            $upcomingPrices[$date->format('Y-m-d')] = $this->searchService->getDynamicPrice($chambre, $date);
        }

        $chambresSimilaires = Chambre::with(['typeChambre', 'propriete', 'medias', 'avis'])
            ->where('id', '!=', $chambre->id)
            ->where('id_type_chambre', $chambre->id_type_chambre)
            ->take(3)
            ->get();

        return view('chambres.show', compact('chambre', 'chambresSimilaires', 'upcomingPrices'));
    }

    public function demanderVisite(Request $request, $id)
    {
        $chambre = Chambre::findOrFail($id);

        $request->validate([
            'date_visite_souhaitee' => 'required|date|after:today',
            'telephone' => 'required|string|max:20',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour programmer une visite.');
        }

        // On crée ou récupère le Client lié au User
        $client = \App\Models\Client::firstOrCreate(
            ['id_utilisateur' => Auth::id()],
            ['telephone' => $request->telephone, 'adresse' => '']
        );

        $demande = new \App\Models\DemandeVisite([
            'id_client' => $client->id,
            'id_chambre' => $chambre->id,
            'date_demande' => now(),
            'date_visite_souhaitee' => Carbon::parse($request->date_visite_souhaitee),
            'statut' => 'en_attente',
            'message' => 'Téléphone fourni: ' . $request->telephone,
        ]);

        $demande->save();

        // On redirige directement vers le paiement Leekpay
        return redirect()->route('paiement.leekpay.initiate.visit', $demande->id);
    }

    public function contact()
    {
        return view('contact');
    }

    public function apropos()
    {
        return view('a-propos');
    }
}
