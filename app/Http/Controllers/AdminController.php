<?php

namespace App\Http\Controllers;

// namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chambre;
use App\Models\Reservation;
use App\Models\Client;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DemandeVisite;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $stats = [
            'total_reservations' => Reservation::count(),
            'reservations_en_cours' => Reservation::where('statut', 'confirmee')->count(),
            'montant_total' => Paiement::where('statut', 'valide')->sum('montant'),
            'montant_aujourdhui' => Paiement::where('statut', 'valide')
                ->whereDate('date_paiement', today())
                ->sum('montant'),
            'montant_mois' => Paiement::where('statut', 'valide')
                ->whereMonth('date_paiement', now()->month)
                ->whereYear('date_paiement', now()->year)
                ->sum('montant'),
            'montant_annee' => Paiement::where('statut', 'valide')
                ->whereYear('date_paiement', now()->year)
                ->sum('montant'),
            'reservations_prochaines' => Reservation::where('date_arrivee', '>=', now())
                ->where('date_arrivee', '<=', now()->addDays(7))
                ->where('statut', 'confirmee')
                ->count(),
            'taux_occupation' => $this->calculerTauxOccupation(),
            'demandes_visite_en_attente' => DemandeVisite::where('statut', 'en_attente')->count(),
            'total_chambres' => Chambre::count(),
            'commandes_services_en_attente' => \App\Models\CommandeService::where('statut', 'en_attente')->count(),
            'locations_vehicules_actives' => \App\Models\LocationVehicule::whereIn('statut', ['confirmee', 'en_cours'])->count(),
        ];

        $reservationsRecentes = Reservation::with(['client.user', 'details.chambre.propriete'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $paiementsRecents = Paiement::with(['reservation.client.user'])
            ->where('statut', 'valide')
            ->orderBy('date_paiement', 'desc')
            ->take(5)
            ->get();

        $commandesRecentes = \App\Models\CommandeService::with(['client.user', 'details.service'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $locationsRecentes = \App\Models\LocationVehicule::with(['client.user', 'vehicule'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'reservationsRecentes', 'paiementsRecents', 'commandesRecentes', 'locationsRecentes'));
    }

    // In AdminController
    public function reservationsStats(Request $request)
    {
        $labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $data = [];

        $reservations = Reservation::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        foreach (range(1, 12) as $month) {
            $data[] = $reservations[$month] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $data,
        ]);
    }

    public function occupancyStats(Request $request)
    {
        $labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $data = [];

        $totalAppartements = Chambre::count();
        if ($totalAppartements > 0) {
            foreach (range(1, 12) as $month) {
                $startOfMonth = now()->setYear(now()->year)->setMonth($month)->startOfMonth();
                $endOfMonth = now()->setYear(now()->year)->setMonth($month)->endOfMonth();

                $count = Reservation::where('statut', 'confirmee')
                    ->where('date_arrivee', '<=', $endOfMonth)
                    ->where('date_depart', '>=', $startOfMonth)
                    ->count();

                $data[] = round(($count / $totalAppartements) * 100, 1);
            }
        } else {
            $data = array_fill(0, 12, 0);
        }

        return response()->json([
            'labels' => $labels,
            'values' => $data,
        ]);
    }

    /**
     * API combinée pour les stats des graphiques du dashboard
     */
    public function chartStats()
    {
        $labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

        // Réservations par mois
        $reservations = Reservation::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $reservationData = [];
        foreach (range(1, 12) as $month) {
            $reservationData[] = $reservations[$month] ?? 0;
        }

        // Revenus par mois
        $revenus = Paiement::selectRaw('MONTH(date_paiement) as month, SUM(montant) as total')
            ->where('statut', 'valide')
            ->whereYear('date_paiement', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $revenusData = [];
        foreach (range(1, 12) as $month) {
            $revenusData[] = (float) ($revenus[$month] ?? 0);
        }

        return response()->json([
            'labels' => $labels,
            'reservations' => $reservationData,
            'revenus' => $revenusData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    private function calculerTauxOccupation()
    {
        $totalappartement = Chambre::count();
        $reservationsActives = Reservation::where('statut', 'confirmee')
            ->where('date_arrivee', '<=', now())
            ->where('date_depart', '>=', now())
            ->count();

        return $totalappartement > 0 ? ($reservationsActives / $totalappartement) * 100 : 0;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Display a listing of the users.
     */
    public function users(Request $request)
    {
        $query = User::with(['client', 'administrateur']);

        // Recherche simple
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        
        $stats = [
            'total' => User::count(),
            'admins' => User::where('type_utilisateur', 'admin')->count(),
            'clients' => User::where('type_utilisateur', 'client')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.users', compact('users', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'type_utilisateur' => 'required|in:admin,client',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'telephone' => $request->telephone,
            'type_utilisateur' => $request->type_utilisateur,
            'statut' => 'actif',
        ]);

        return back()->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUser(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'type_utilisateur' => 'required|in:admin,client',
            'telephone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'type_utilisateur' => $request->type_utilisateur,
            'telephone' => $request->telephone,
        ]);

        return back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\User $user)
    {
        // Empêcher la suppression du propre compte
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
