<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\Propriete;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinanceReportExport;
use Stripe\Stripe;
use Stripe\Refund;
use App\Services\NotificationService;
use App\Models\LocationVehicule;
use App\Models\CommandeService;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Affiche le tableau de bord financier
     */

    public function index(Request $request)
    {
        $dateDebut = $request->query('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->query('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $statut = $request->query('statut');
        $proprieteId = $request->query('propriete_id');
        $methodePaiement = $request->query('methode_paiement');

        $query = Paiement::with(['reservation.client.user', 'reservation.details.chambre.propriete'])
            ->orderBy('date_paiement', 'desc');

        if ($statut) {
            $query->where('statut', $statut);
        }
        if ($dateDebut) {
            $query->whereDate('date_paiement', '>=', $dateDebut);
        }
        if ($dateFin) {
            $query->whereDate('date_paiement', '<=', $dateFin);
        }
        if ($proprieteId) {
            $query->whereHas('reservation.details.chambre', function ($q) use ($proprieteId) {
                $q->where('id_propriete', $proprieteId);
            });
        }
        if ($methodePaiement) {
            $query->where('methode_paiement', $methodePaiement);
        }

        $paiements = $query->paginate(15);

        $statistiques = [
            'total_jour' => Paiement::whereDate('date_paiement', Carbon::today())
                ->where('statut', 'valide')
                ->sum('montant'),
            'total_mois' => Paiement::whereMonth('date_paiement', Carbon::now()->month)
                ->where('statut', 'valide')
                ->sum('montant'),
            'total_annee' => Paiement::whereYear('date_paiement', Carbon::now()->year)
                ->where('statut', 'valide')
                ->sum('montant'),
            'en_attente' => Paiement::where('statut', 'en_attente')->count(),
            'refuse' => Paiement::where('statut', 'refuse')->count(),
            'rembourse' => Paiement::where('statut', 'rembourse')->sum('montant_rembourse'),
            'revpar' => $this->calculerRevPAR($dateDebut, $dateFin),
            'taux_occupation' => $this->calculerTauxOccupation($dateDebut, $dateFin),
        ];

        $proprietes = Propriete::pluck('nom', 'id')->toArray();
        $methodesPaiement = Paiement::select('methode_paiement')->distinct()->pluck('methode_paiement')->toArray();

        return view('admin.finance.index', compact('paiements', 'statistiques', 'dateDebut', 'dateFin', 'proprietes', 'methodesPaiement'));
    }

    /**
     * Génère des rapports financiers détaillés
     */
    public function rapports(Request $request)
    {
        $debut = $request->query('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fin = $request->query('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $proprieteId = $request->query('propriete_id');

        $query = Paiement::with(['reservation.details.chambre.propriete'])
            ->where('statut', 'valide')
            ->whereBetween('date_paiement', [$debut, $fin]);

        if ($proprieteId) {
            $query->whereHas('reservation.details.chambre', function ($q) use ($proprieteId) {
                $q->where('id_propriete', $proprieteId);
            });
        }

        $paiements = $query->get();

        // Calculer les taxes (hypothèse : 10% de TVA + taxe de séjour fixe par jour)
        $tauxTVA = 0.10; // 10%
        $taxeSejourParJour = 1000; // Exemple : 1000 F CFA par jour
        $reservations = Reservation::whereBetween('date_arrivee', [$debut, $fin])
            ->where('statut', 'confirmee')
            ->get();

        $taxes = [
            'tva' => $paiements->sum('montant') * $tauxTVA,
            'taxe_sejour' => $reservations->sum(function ($reservation) use ($taxeSejourParJour) {
                return Carbon::parse($reservation->date_arrivee)->diffInDays(Carbon::parse($reservation->date_depart)) * $taxeSejourParJour;
            }),
        ];

        // Statistiques financières détaillées
        $statistiques = [
            'total_periode' => $paiements->sum('montant'),
            'nombre_reservations' => $reservations->count(),
            'moyenne_reservation' => $paiements->avg('montant') ?? 0,
            'par_propriete' => $paiements->groupBy('reservation.details.chambre.id_propriete')
                ->map->sum('montant'),
            'par_type_chambre' => $paiements->groupBy('reservation.details.chambre.id_type_chambre')
                ->map->sum('montant'),
            'taxes' => $taxes,
            'revpar' => $this->calculerRevPAR($debut, $fin),
        ];

        $proprietes = Propriete::pluck('nom', 'id')->toArray();

        return view('admin.finance.rapports', compact('paiements', 'statistiques', 'debut', 'fin', 'proprietes'));
    }

    /**
     * Affiche l'historique des transactions
     */
    public function transactions(Request $request)
    {
        $dateDebut = $request->query('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->query('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $statut = $request->query('statut');
        $methodePaiement = $request->query('methode_paiement');

        $query = Paiement::with(['reservation.client.user', 'reservation.details.chambre.propriete'])
            ->orderBy('date_paiement', 'desc');

        if ($statut) {
            $query->where('statut', $statut);
        }
        if ($dateDebut) {
            $query->whereDate('date_paiement', '>=', $dateDebut);
        }
        if ($dateFin) {
            $query->whereDate('date_paiement', '<=', $dateFin);
        }
        if ($methodePaiement) {
            $query->where('methode_paiement', $methodePaiement);
        }

        $transactions = $query->paginate(20);
        $methodesPaiement = Paiement::select('methode_paiement')->distinct()->pluck('methode_paiement')->toArray();

        return view('admin.finance.transactions', compact('transactions', 'dateDebut', 'dateFin', 'methodesPaiement'));
    }

    /**
     * Traite un remboursement
     */
    // public function refund(Request $request, Paiement $paiement) //1
    // {
    //     $request->validate([
    //         'montant' => 'required|numeric|min:1|max:' . $paiement->montant,
    //         'raison' => 'required|string|max:500',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         Stripe::setApiKey(config('services.stripe.secret'));
    //         $refund = Refund::create([
    //             'charge' => $paiement->reference_transaction,
    //             'amount' => intval($request->montant * 100), // Stripe attend les montants en centimes
    //             'reason' => $request->raison,
    //         ]);

    //         $paiement->update([
    //             'statut' => 'rembourse',
    //             'notes' => $paiement->notes . "\nRemboursement de {$request->montant} F CFA le " . now() . " : {$request->raison}",
    //         ]);

    //         $this->logAction('refund', $paiement, [
    //             'montant' => $request->montant,
    //             'raison' => $request->raison,
    //         ]);

    //         // Notifier le client
    //         $message = "Un remboursement de {$request->montant} F CFA a été effectué pour votre réservation {$paiement->reservation->reference}. Raison : {$request->raison}.";
    //         $this->notificationService->sendNotification(
    //             $paiement->reservation->client->user,
    //             $paiement->reservation,
    //             'email',
    //             'Remboursement effectué',
    //             $message
    //         );

    //         DB::commit();
    //         return redirect()->route('admin.finance.transactions')
    //             ->with('success', 'Remboursement de ' . number_format($request->montant, 0, ',', ' ') . ' F CFA effectué avec succès.');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         Log::error('Erreur lors du remboursement', ['error' => $e->getMessage()]);
    //         return back()->with('error', 'Erreur lors du remboursement : ' . $e->getMessage());
    //     }
    // }

    /**
     * Traite un remboursement
     */
    public function refund(Request $request, Paiement $paiement)
    {
        $request->validate([
            'montant' => 'required|numeric|min:1|max:' . ($paiement->montant - $paiement->montant_rembourse),
            'raison' => 'required|string|max:500',
        ]);

        // Vérifier si le paiement est déjà totalement remboursé
        if ($paiement->statut === 'rembourse' && $paiement->montant_rembourse >= $paiement->montant) {
            return back()->with('error', 'Ce paiement a déjà été totalement remboursé.');
        }

        // Vérifier que la référence de transaction existe
        if (empty($paiement->reference_transaction)) {
            return back()->with('error', 'Erreur : Aucune référence de transaction valide pour ce paiement.');
        }

        DB::beginTransaction();
        try {
            // Configurer Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Vérifier si la charge existe dans Stripe
            $charge = \Stripe\Charge::retrieve($paiement->reference_transaction);
            if (!$charge) {
                throw new \Exception('Charge introuvable dans Stripe.');
            }

            // Effectuer le remboursement (XOF est une devise sans décimales)
            $refund = Refund::create([
                'charge' => $paiement->reference_transaction,
                'amount' => intval($request->montant), // Pas de multiplication par 100 pour XOF
                'reason' => $request->raison,
            ]);

            // Mettre à jour le paiement
            $nouveauMontantRembourse = $paiement->montant_rembourse + $request->montant;
            $nouveauStatut = ($nouveauMontantRembourse >= $paiement->montant) ? 'rembourse' : $paiement->statut;

            $paiement->update([
                'statut' => $nouveauStatut,
                'montant_rembourse' => $nouveauMontantRembourse,
                'notes' => $paiement->notes . "\nRemboursement de {$request->montant} F CFA le " . now() . " : {$request->raison}",
            ]);

            // Mettre à jour le prix total de la réservation
            if ($paiement->reservation) {
                $nouveauPrixTotal = $paiement->reservation->prix_total - $request->montant;
                $paiement->reservation->update([
                    'prix_total' => max(0, $nouveauPrixTotal),
                ]);
            }

            // Journaliser l'action
            $this->logAction('refund', $paiement, [
                'montant' => $request->montant,
                'raison' => $request->raison,
                'reservation_id' => $paiement->reservation ? $paiement->reservation->id : null,
            ]);

            // Notifier le client
            $message = "Un remboursement de {$request->montant} F CFA a été effectué pour votre réservation {$paiement->reservation->reference}. Raison : {$request->raison}.";
            $this->notificationService->sendNotification(
                $paiement->reservation->client->user,
                $paiement->reservation,
                'email',
                'Remboursement effectué',
                $message
            );

            DB::commit();
            return redirect()->route('admin.finance.transactions')
                ->with('success', 'Remboursement de ' . number_format($request->montant, 0, ',', ' ') . ' F CFA effectué avec succès.');
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            DB::rollback();
            Log::error('Erreur Stripe InvalidRequestException : ' . $e->getMessage());
            return back()->with('error', 'Erreur Stripe : ' . $e->getMessage());
        } catch (\Stripe\Exception\CardException $e) {
            DB::rollback();
            Log::error('Erreur Stripe CardException : ' . $e->getMessage());
            return back()->with('error', 'Erreur de carte : ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors du remboursement', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors du remboursement : ' . $e->getMessage());
        }
    }

    /**
     * Génère des prévisions financières
     */
    public function previsions(Request $request)
    {
        $dateDebut = $request->query('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->query('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Récupérer les réservations confirmées pour la période
        $reservations = Reservation::whereIn('statut', ['confirmee', 'en_attente_paiement'])
            ->whereBetween('date_arrivee', [$dateDebut, $dateFin])
            ->get();

        $previsions = [
            'revenu_attendu' => $reservations->sum('prix_total'),
            'reservations_confirmees' => $reservations->where('statut', 'confirmee')->count(),
            'reservations_en_attente' => $reservations->where('statut', 'en_attente_paiement')->count(),
            'par_propriete' => $reservations->groupBy('details.chambre.id_propriete')
                ->map->sum('prix_total'),
        ];

        $proprietes = Propriete::pluck('nom', 'id')->toArray();

        return view('admin.finance.previsions', compact('previsions', 'dateDebut', 'dateFin', 'proprietes'));
    }

    /**
     * Exporte les rapports financiers au format Excel
     */
    public function export(Request $request)
    {
        $debut = $request->query('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fin = $request->query('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        return Excel::download(new FinanceReportExport($debut, $fin), 'rapport_financier_' . $debut . '_to_' . $fin . '.xlsx');
    }

    /**
     * Affiche l'historique des audits financiers
     */
    public function audit(Request $request)
    {
        $dateDebut = $request->query('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->query('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $audits = AuditLog::whereIn('action', ['refund', 'payment_processed', 'status_updated'])
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.finance.audit', compact('audits', 'dateDebut', 'dateFin'));
    }

    /**
     * Calcule le RevPAR (Revenu par chambre disponible)
     */
    private function calculerRevPAR($dateDebut, $dateFin)
    {
        $totalappartement = Chambre::count();
        $jours = Carbon::parse($dateDebut)->diffInDays(Carbon::parse($dateFin)) + 1;
        $appartementDisponibles = $totalappartement * $jours;

        $revenu = Paiement::where('statut', 'valide')
            ->whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->sum('montant');

        return $appartementDisponibles > 0 ? $revenu / $appartementDisponibles : 0;
    }

    /**
     * Calcule le taux d’occupation
     */
    private function calculerTauxOccupation($dateDebut, $dateFin)
    {
        $totalappartement = Chambre::count();
        $jours = Carbon::parse($dateDebut)->diffInDays(Carbon::parse($dateFin)) + 1;
        $appartementDisponibles = $totalappartement * $jours;

        $joursOccupes = Reservation::where('statut', 'confirmee')
            ->where('date_arrivee', '<=', $dateFin)
            ->where('date_depart', '>=', $dateDebut)
            ->with('details.chambre')
            ->get()
            ->sum(function ($reservation) use ($dateDebut, $dateFin) {
                $debut = Carbon::parse($reservation->date_arrivee)->max(Carbon::parse($dateDebut));
                $fin = Carbon::parse($reservation->date_depart)->min(Carbon::parse($dateFin));
                $jours = $debut->diffInDays($fin) + 1;
                $nombreappartement = $reservation->details->count();
                return $jours > 0 ? $jours * $nombreappartement : 0;
            });

        return $appartementDisponibles > 0 ? ($joursOccupes / $appartementDisponibles) * 100 : 0;
    }

    private function logAction($action, $model, array $details)
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'details' => json_encode($details),
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la journalisation : ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire d'encaissement unifié
     */
    public function createEncaissement()
    {
        // Récupérer les réservations avec un reste à payer
        $reservations = Reservation::with('client.user')
            ->whereIn('statut', ['en_attente_paiement', 'acompte_paye', 'confirmee'])
            ->get()
            ->filter(function($r) {
                return ($r->prix_total - $r->acompte_paye) > 0;
            });

        // Récupérer les locations de véhicules non payées ou partielles
        $locations = LocationVehicule::with(['client.user', 'vehicule'])
            ->whereIn('statut_paiement', ['non_paye', 'partiel'])
            ->get();

        // Récupérer les commandes de services non payées ou partielles
        $commandes = CommandeService::with(['client.user'])
            ->whereIn('statut_paiement', ['non_paye', 'partiel'])
            ->get();

        return view('admin.finance.create-encaissement', compact('reservations', 'locations', 'commandes'));
    }

    /**
     * Enregistre un encaissement pour n'importe quel type de service
     */
    public function storeEncaissement(Request $request)
    {
        $request->validate([
            'type' => 'required|in:reservation,location_vehicule,commande_service',
            'item_id' => 'required',
            'montant' => 'required|numeric|min:1',
            'methode_paiement' => 'required|string|in:especes,virement,carte_credit,mobile_money,autre',
            'reference_transaction' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $paiementData = [
                'montant' => $request->montant,
                'date_paiement' => now(),
                'methode_paiement' => $request->methode_paiement,
                'reference_transaction' => $request->reference_transaction ?? 'MANUAL-' . strtoupper(uniqid()),
                'statut' => 'valide',
                'id_admin_validation' => Auth::id(),
                'notes' => $request->notes,
            ];

            $model = null;
            $clientUser = null;
            $reference = "";

            if ($request->type === 'reservation') {
                $model = Reservation::findOrFail($request->item_id);
                $paiementData['id_reservation'] = $model->id;
                
                $nouveauTotalPaye = $model->acompte_paye + $request->montant;
                $nouveauStatut = ($nouveauTotalPaye >= $model->prix_total) ? 'confirmee' : $model->statut;
                
                $model->update([
                    'acompte_paye' => $nouveauTotalPaye,
                    'statut' => $nouveauStatut,
                ]);
                
                $clientUser = $model->client->user;
                $reference = $model->reference;

            } elseif ($request->type === 'location_vehicule') {
                $model = LocationVehicule::findOrFail($request->item_id);
                $paiementData['id_location_vehicule'] = $model->id;
                $paiementData['id_reservation'] = $model->id_reservation;

                // Calculer le nouveau statut de paiement simplifié (pour l'instant full ou partiel)
                // Idéalement on devrait suivre le total payé aussi dans location_vehicule
                $nouveauStatutP = ($request->montant >= $model->prix_total) ? 'paye' : 'partiel';
                
                $model->update([
                    'statut_paiement' => $nouveauStatutP,
                ]);
                
                $clientUser = $model->client->user;
                $reference = "Location #" . $model->id;

            } elseif ($request->type === 'commande_service') {
                $model = CommandeService::findOrFail($request->item_id);
                $paiementData['id_commande_service'] = $model->id;
                $paiementData['id_reservation'] = $model->id_reservation;

                $nouveauStatutP = ($request->montant >= $model->prix_total) ? 'paye' : 'partiel';
                
                $model->update([
                    'statut_paiement' => $nouveauStatutP,
                ]);

                $clientUser = $model->client->user;
                $reference = "Commande #" . $model->id;
            }

            $paiement = Paiement::create($paiementData);

            // Logger l'audit
            $this->logAction('manual_unified_payment', $paiement, [
                'type_origine' => $request->type,
                'item_id' => $request->item_id,
                'montant' => $request->montant,
                'reference' => $reference
            ]);

            // Notification
            if ($clientUser) {
                try {
                    $this->notificationService->sendNotification(
                        $clientUser,
                        $model,
                        'email',
                        'Paiement reçu',
                        "Nous confirmons la réception de votre paiement de " . number_format($request->montant, 0, ',', ' ') . " FCFA pour {$reference}."
                    );
                } catch (\Exception $e) {
                    Log::warning('Notification non envoyée: ' . $e->getMessage());
                }
            }

            DB::commit();
            return redirect()->route('admin.finance.transactions')
                ->with('success', 'Encaissement de ' . number_format($request->montant, 0, ',', ' ') . ' FCFA enregistré avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'encaissement unifié', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Liste des paiements en attente de validation (Mobile Money, etc.)
     */
    public function pendingPayments()
    {
        $pendingPayments = Paiement::with(['reservation.client.user', 'locationVehicule.client.user', 'commandeService.client.user'])
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.finance.pending', compact('pendingPayments'));
    }

    /**
     * Valider un paiement déclaré par un client
     */
    public function approvePayment(Request $request, Paiement $paiement)
    {
        DB::beginTransaction();
        try {
            $paiement->update([
                'statut' => 'valide',
                'id_admin_validation' => Auth::id(),
                'notes' => ($paiement->notes ? $paiement->notes . " | " : "") . "Validé par " . Auth::user()->name
            ]);

            $model = null;
            $clientUser = null;
            $reference = "";

            // Mettre à jour le modèle lié
            if ($paiement->id_reservation && !$paiement->id_location_vehicule && !$paiement->id_commande_service) {
                $model = Reservation::find($paiement->id_reservation);
                if ($model) {
                    $nouveauTotalPaye = $model->acompte_paye + $paiement->montant;
                    $model->update([
                        'acompte_paye' => $nouveauTotalPaye,
                        'statut' => ($nouveauTotalPaye >= $model->prix_total) ? 'confirmee' : $model->statut
                    ]);
                    $clientUser = $model->client ? $model->client->user : null;
                    $reference = $model->reference;
                }
            } elseif ($paiement->id_location_vehicule) {
                $model = LocationVehicule::find($paiement->id_location_vehicule);
                if ($model) {
                    $model->update([
                        'statut_paiement' => 'paye',
                        'statut' => ($model->statut === 'en_attente_validation' || $model->statut === 'en_attente') ? 'confirmee' : $model->statut
                    ]);
                    $clientUser = $model->client ? $model->client->user : null;
                    $reference = "Location #" . $model->id;
                }
            } elseif ($paiement->id_commande_service) {
                $model = CommandeService::find($paiement->id_commande_service);
                if ($model) {
                    $model->update([
                        'statut_paiement' => 'paye',
                        'statut' => ($model->statut === 'en_attente_validation' || $model->statut === 'en_attente') ? 'confirmee' : $model->statut
                    ]);
                    $clientUser = $model->client ? $model->client->user : null;
                    $reference = "Commande #" . $model->id;
                }
            }


            // Notification
            if ($clientUser) {
                try {
                    $this->notificationService->sendNotification(
                        $clientUser,
                        $model,
                        'email',
                        'Paiement validé',
                        "Votre paiement de " . number_format($paiement->montant, 0, ',', ' ') . " FCFA pour {$reference} a été validé. Merci de votre confiance."
                    );
                } catch (\Exception $e) {
                    Log::warning('Notification non envoyée: ' . $e->getMessage());
                }
            }

            DB::commit();
            return back()->with('success', 'Paiement validé avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur validation paiement', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Rejeter un paiement déclaré
     */
    public function rejectPayment(Request $request, Paiement $paiement)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $paiement->update([
            'statut' => 'rejete',
            'id_admin_validation' => Auth::id(),
            'notes' => ($paiement->notes ? $paiement->notes . " | " : "") . "REJETÉ: " . $request->reason
        ]);

        return back()->with('info', 'Paiement rejeté.');
    }
}
