<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\DemandeVisite;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservationService
{
    /**
     * Récupère les statistiques globales pour le dashboard
     */
    public function getDashboardStats()
    {
        return [
            'reservations_totales' => Reservation::count(),
            'reservations_cette_semaine' => Reservation::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
            'reservations_en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'revenus_aujourdhui' => DB::table('paiements')->whereDate('date_paiement', Carbon::today())->sum('montant'),
            'revenus_ce_mois' => DB::table('paiements')->whereMonth('date_paiement', Carbon::now()->month)->sum('montant'),
            'demandes_visite_attente' => DemandeVisite::where('statut', 'en_attente')->count(),
        ];
    }

    /**
     * Gère la programmation d'une visite
     */
    public function scheduleVisite(DemandeVisite $demande, array $data)
    {
        return DB::transaction(function () use ($demande, $data) {
            $demande->update([
                'date_confirmation' => $data['date_visite'],
                'heure_debut' => $data['heure_visite'],
                'statut' => 'programmee',
                'notes_admin' => $data['notes_admin'] ?? null,
                'id_admin_confirmation' => Auth::id(),
            ]);

            // Logique de notification ici...
            
            return $demande;
        });
    }

    /**
     * Récupère les données pour les graphiques
     */
    public function getMonthlyStats()
    {
        return Reservation::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
