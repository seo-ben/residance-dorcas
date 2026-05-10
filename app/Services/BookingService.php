<?php

namespace App\Services;

use App\Models\Chambre;
use App\Models\Reservation;
use App\Models\DetailReservation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Seuils de réduction en jours et pourcentages correspondants.
     */
    protected $seuilsReduction = [
        7 => 5,    // 5% de réduction pour 7 jours ou plus
        14 => 10,  // 10% de réduction pour 14 jours ou plus
        30 => 15   // 15% de réduction pour 30 jours ou plus
    ];

    public function calculatePrice(Chambre $chambre, $dateArrivee, $dateDepart)
    {
        $start = Carbon::parse($dateArrivee);
        $end = Carbon::parse($dateDepart);
        $nbJours = $start->diffInDays($end);
        
        $prixBase = $chambre->prix_base * $nbJours;

        // 1. Réduction Durée (existing)
        $reductionDuree = $this->calculateReduction($nbJours, $prixBase);
        $prixApresDuree = $prixBase - $reductionDuree['montant'];

        // 2. Réduction Fidélité (Booking logic: Genius)
        $reductionFidelite = 0;
        $geniusLevel = 0;
        if (Auth::check()) {
            $client = Auth::user();
            $geniusLevel = $this->loyaltyService->getLoyaltyLevel($client);
            $prixFinal = $this->loyaltyService->applyLoyaltyDiscount($prixApresDuree, $geniusLevel);
            $reductionFidelite = $prixApresDuree - $prixFinal;
        } else {
            $prixFinal = $prixApresDuree;
        }
        
        return [
            'nb_jours' => $nbJours,
            'prix_original' => $prixBase,
            'reduction_duree' => $reductionDuree['montant'],
            'reduction_fidelite' => $reductionFidelite,
            'genius_level' => $geniusLevel,
            'prix_total' => $prixFinal,
        ];
    }

    /**
     * Calcule le montant de la réduction.
     */
    protected function calculateReduction($nbJours, $prixBase)
    {
        $pourcentage = 0;

        foreach ($this->seuilsReduction as $seuil => $taux) {
            if ($nbJours >= $seuil) {
                $pourcentage = $taux;
            } else {
                break;
            }
        }

        return [
            'pourcentage' => $pourcentage,
            'montant' => ($prixBase * $pourcentage) / 100,
        ];
    }

    /**
     * Vérifie la disponibilité d'une chambre pour une période donnée.
     */
    public function checkAvailability($chambreId, $dateArrivee, $dateDepart, $excludeReservationId = null)
    {
        $chambre = Chambre::findOrFail($chambreId);

        // Vérifier les réservations existantes
        $conflictualReservations = DetailReservation::whereHas('reservation', function ($query) use ($dateArrivee, $dateDepart, $excludeReservationId) {
            $query->whereNotIn('statut', ['annulee', 'expiree']);
            
            if ($excludeReservationId) {
                $query->where('id', '!=', $excludeReservationId);
            }

            $query->where(function ($q) use ($dateArrivee, $dateDepart) {
                $q->whereBetween('date_arrivee', [$dateArrivee, $dateDepart])
                    ->orWhereBetween('date_depart', [$dateArrivee, $dateDepart])
                    ->orWhere(function ($innerQ) use ($dateArrivee, $dateDepart) {
                        $innerQ->where('date_arrivee', '<=', $dateArrivee)
                            ->where('date_depart', '>=', $dateDepart);
                    });
            });
        })->where('id_chambre', $chambreId)->exists();

        if ($conflictualReservations) {
            return false;
        }

        // Vérifier les périodes d'indisponibilité manuelles
        $isUnavailable = $chambre->periodesIndisponibilite()
            ->where(function ($query) use ($dateArrivee, $dateDepart) {
                $query->whereBetween('date_debut', [$dateArrivee, $dateDepart])
                    ->orWhereBetween('date_fin', [$dateArrivee, $dateDepart])
                    ->orWhere(function ($q) use ($dateArrivee, $dateDepart) {
                        $q->where('date_debut', '<=', $dateArrivee)
                            ->where('date_fin', '>=', $dateDepart);
                    });
            })->exists();

        return !$isUnavailable;
    }

    /**
     * Trouve les périodes disponibles pour une chambre dans l'année en cours.
     */
    public function getAvailablePeriods($chambreId)
    {
        $chambre = Chambre::findOrFail($chambreId);
        $aujourdhui = Carbon::now();
        $finAnnee = Carbon::now()->endOfYear();
        $periodes = [];

        // Récupérer toutes les indisponibilités (réservations + périodes manuelles)
        $indisponibilites = DetailReservation::whereHas('reservation', function ($query) {
                $query->whereNotIn('statut', ['annulee', 'expiree']);
            })
            ->where('id_chambre', $chambreId)
            ->with(['reservation' => fn($q) => $q->select('id', 'date_arrivee', 'date_depart')])
            ->get()
            ->map(fn($d) => [
                'debut' => Carbon::parse($d->reservation->date_arrivee),
                'fin' => Carbon::parse($d->reservation->date_depart)
            ])
            ->merge(
                $chambre->periodesIndisponibilite->map(fn($p) => [
                    'debut' => Carbon::parse($p->date_debut),
                    'fin' => Carbon::parse($p->date_fin)
                ])
            )
            ->sortBy('debut');

        if ($indisponibilites->isEmpty()) {
            return [['debut' => $aujourdhui->format('Y-m-d'), 'fin' => $finAnnee->format('Y-m-d')]];
        }

        $dateCursor = $aujourdhui->copy();

        foreach ($indisponibilites as $indispo) {
            if ($dateCursor < $indispo['debut']) {
                $periodes[] = [
                    'debut' => $dateCursor->format('Y-m-d'),
                    'fin' => $indispo['debut']->subDay()->format('Y-m-d')
                ];
            }
            $dateCursor = $indispo['fin']->isAfter($dateCursor) ? $indispo['fin']->addDay() : $dateCursor;
        }

        if ($dateCursor < $finAnnee) {
            $periodes[] = [
                'debut' => $dateCursor->format('Y-m-d'),
                'fin' => $finAnnee->format('Y-m-d')
            ];
        }

        return $periodes;
    }

    /**
     * Crée ou met à jour une réservation.
     */
    public function saveReservation(array $data, ?Reservation $reservation = null)
    {
        $chambre = Chambre::findOrFail($data['chambre_id']);
        $pricing = $this->calculatePrice($chambre, $data['date_arrivee'], $data['date_depart']);

        if (!$reservation) {
            $reservation = new Reservation();
            
            // Get the client record associated with the authenticated user
            $client = \App\Models\Client::firstOrCreate(['id_utilisateur' => Auth::id()]);
            $reservation->id_client = $client->id;
            
            $reservation->reference = 'RES-' . strtoupper(Str::random(8));
        }

        $reservation->fill([
            'date_arrivee' => $data['date_arrivee'],
            'date_depart' => $data['date_depart'],
            'prix_total' => $pricing['prix_total'],
            'prix_original' => $pricing['prix_original'],
            'type_reservation' => 'appartement', // Valeur par défaut pour éviter l'erreur NOT NULL
            'reduction_montant' => ($pricing['reduction_duree'] + $pricing['reduction_fidelite']),
            'reduction_pourcentage' => $pricing['prix_original'] > 0 ? (($pricing['reduction_duree'] + $pricing['reduction_fidelite']) / $pricing['prix_original']) * 100 : 0,
            'id_demande_visite' => $data['visite_id'] ?? null,
            'notes_client' => $data['notes'] ?? null,
            'statut' => $data['statut'] ?? 'brouillon',
        ]);

        $reservation->save();

        // Gestion des détails (id_chambre)
        $detail = $reservation->details()->firstOrNew(['id_reservation' => $reservation->id]);
        $detail->fill([
            'id_chambre' => $chambre->id,
            'prix_unitaire' => $chambre->prix_base,
            'nb_nuits' => $pricing['nb_jours'],
            'total' => $pricing['prix_total']
        ])->save();

        return $reservation;
    }
}
