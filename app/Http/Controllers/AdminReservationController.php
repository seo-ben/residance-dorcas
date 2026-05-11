<?php

namespace App\Http\Controllers;

use App\Models\DemandeVisite;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Chambre;
use Illuminate\Support\Str;
use App\Models\Equipement;
use App\Models\Paiement;
use App\Models\Propriete;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Communication;
use App\Notifications\ReservationNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Checkout\Session;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReservationsExport;
use Illuminate\Support\Facades\Notification;

class AdminReservationController extends Controller
{

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    // /**
    //  * Affiche les détails d'une réservation
    //  */
    // public function show(Reservation $reservation)
    // {
    //     $reservation->load(['client', 'details.chambre.propriete', 'details.chambre.medias', 'paiements']);
        
    //     return view('admin.reservations.show', compact('reservation'));
    // }

    // /**
    //  * Met à jour le statut d'une réservation
    //  */
    // public function updateStatus(Request $request, Reservation $reservation)
    // {
    //     $validated = $request->validate([
    //         'statut' => 'required|in:en_attente_paiement,acompte_paye,confirmee,annulee,terminee',
    //         'notes_admin' => 'nullable|string|max:500',
    //     ]);

    //     $reservation->update([
    //         'statut' => $validated['statut'],
    //         'notes_admin' => $validated['notes_admin'],
    //         'id_admin_modification' => auth()->id(),
    //         'date_modification' => now(),
    //     ]);

    //     return redirect()->route('admin.reservations.show', $reservation->id)
    //         ->with('success', 'Le statut de la réservation a été mis à jour.');
    // }

    /**
     * Affiche la liste des demandes de visite avec filtres
     */
    // public function demandesVisite(Request $request)
    // {
    //     $statut = $request->query('statut');
    //     $dateDebut = $request->query('date_debut');
    //     $dateFin = $request->query('date_fin');

    //     // Par défaut, on affiche les demandes de la semaine en cours
    //     if (!$dateDebut && !$dateFin) {
    //         $dateDebut = Carbon::now()->startOfWeek()->format('Y-m-d');
    //         $dateFin = Carbon::now()->endOfWeek()->format('Y-m-d');
    //     }

    //     $query = DemandeVisite::with(['client', 'chambre.propriete'])
    //         ->orderBy('date_demande', 'desc');

    //     if ($statut) {
    //         $query->where('statut', $statut);
    //     }

    //     if ($dateDebut) {
    //         $query->whereDate('date_demande', '>=', $dateDebut);
    //     }

    //     if ($dateFin) {
    //         $query->whereDate('date_demande', '<=', $dateFin);
    //     }

    //     $demandes = $query->paginate(10);

    //     return view('admin.demandes-visite.index', compact('demandes', 'dateDebut', 'dateFin'));
    // }

    // private function calculerTauxOccupation()
    // {
    //     $totalappartement = Chambre::count();
    //     $reservationsActives = Reservation::where('statut', 'confirmee')
    //         ->where('date_arrivee', '<=', now())
    //         ->where('date_depart', '>=', now())
    //         ->count();

    //     return $totalappartement > 0 ? ($reservationsActives / $totalappartement) * 100 : 0;
    // }



    // public function __construct(NotificationService $notificationService)
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:admin');
    //     $this->notificationService = $notificationService;
    // }


    public function index(Request $request)
    {
        $statut = $request->query('statut');
        $dateDebut = $request->query('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->query('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $search = $request->query('search');
        $proprieteId = $request->query('propriete_id');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDirection = $request->query('sort_direction', 'desc');

        $query = Reservation::with(['client.user', 'details.chambre.propriete', 'details.chambre.medias']);

        // Filtre par statut
        if ($statut) {
            $query->where('statut', $statut);
        }

        // Filtre par date de début
        if ($dateDebut) {
            $query->whereDate('date_arrivee', '>=', $dateDebut);
        }

        // Filtre par date de fin
        if ($dateFin) {
            $query->whereDate('date_depart', '<=', $dateFin);
        }

        // Filtre par recherche (référence, nom client, email)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('client.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filtre par propriété
        if ($proprieteId) {
            $query->whereHas('details.chambre', function ($q) use ($proprieteId) {
                $q->where('id_propriete', $proprieteId);
            });
        }
        // Exécuter la requête avec tous les filtres appliqués
        $reservations = $query->orderBy($sortBy, $sortDirection)->paginate(10);
        $proprietes = Propriete::pluck('nom', 'id')->toArray();

        // Statistiques rapides pour le haut de page
        $stats = [
            'arrivees_aujourdhui' => Reservation::whereDate('date_arrivee', today())->where('statut', 'confirmee')->count(),
            'departs_aujourdhui' => Reservation::whereDate('date_depart', today())->where('statut', 'confirmee')->count(),
            'paiements_en_attente' => Reservation::where('statut', 'en_attente_paiement')->count(),
            'total_actifs' => Reservation::whereIn('statut', ['confirmee', 'acompte_paye'])->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'dateDebut', 'dateFin', 'proprietes', 'stats'));
    }

    /**
     * Exporte les réservations au format CSV
     */
    public function exportCsv(Request $request)
    {
        $filename = "reservations_" . now()->format('Y-m-d_H-i') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Référence', 'Client', 'Email', 'Appartement', 'Propriété', 'Arrivée', 'Départ', 'Prix Total', 'Acompte', 'Statut'];

        $callback = function() use($request, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $query = Reservation::with(['client.user', 'details.chambre.propriete', 'details.chambre.typeChambre']);
            
            // Appliquer les mêmes filtres que l'index si nécessaire (simplifié ici pour toutes les résas du mois par défaut)
            $reservations = $query->get();

            foreach ($reservations as $res) {
                fputcsv($file, [
                    $res->reference,
                    optional($res->client->user)->name . ' ' . optional($res->client->user)->prenom,
                    optional($res->client->user)->email,
                    optional($res->details->first()->chambre->typeChambre)->nom,
                    optional($res->details->first()->chambre->propriete)->nom,
                    $res->date_arrivee,
                    $res->date_depart,
                    $res->prix_total,
                    $res->acompte_paye,
                    $res->statut
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Formulaire de création d'une réservation par l'admin
     */
    public function create()
    {
        $clients = User::whereHas('client')->with('client')->get();
        $chambres = Chambre::with('propriete', 'typeChambre')->get();

        return view('admin.reservations.create', compact('clients', 'chambres'));
    }

    /**
     * Enregistre une nouvelle réservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_client' => 'required|exists:clients,id',
            'id_chambre' => 'required|exists:appartement,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee',
            'prix_total' => 'required|numeric|min:0',
            'statut' => 'required|in:en_attente_paiement,confirmee,brouillon',
            'notes_admin' => 'nullable|string|max:1000',
            'montant_paye' => 'nullable|numeric|min:0',
            'methode_paiement' => 'nullable|required_with:montant_paye|string',
            'reference_paiement' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $chambre = Chambre::findOrFail($validated['id_chambre']);

            // Création de la réservation
            $reservation = Reservation::create([
                'id_client' => $validated['id_client'],
                'reference' => 'RES-' . strtoupper(Str::random(8)),
                'date_creation' => now(),
                'date_arrivee' => $validated['date_arrivee'],
                'date_depart' => $validated['date_depart'],
                'statut' => $validated['statut'],
                'prix_total' => $validated['prix_total'],
                'prix_original' => $validated['prix_total'],
                'notes_admin' => $validated['notes_admin'],
                'acompte_paye' => $validated['montant_paye'] ?? 0,
            ]);

            // Détails de la réservation
            $reservation->details()->create([
                'id_chambre' => $validated['id_chambre'],
                'prix_unitaire' => $chambre->prix_base,
                'quantite' => 1,
            ]);

            // Gestion du paiement initial si fourni
            if (!empty($validated['montant_paye']) && $validated['montant_paye'] > 0) {
                Paiement::create([
                    'id_reservation' => $reservation->id,
                    'montant' => $validated['montant_paye'],
                    'date_paiement' => now(),
                    'methode_paiement' => $validated['methode_paiement'],
                    'reference_transaction' => $validated['reference_paiement'] ?? 'MANUAL-' . strtoupper(uniqid()),
                    'statut' => 'valide',
                    'id_admin_validation' => auth()->id(),
                ]);

                // Si payé en totalité, on peut forcer le statut à confirmée
                if ($validated['montant_paye'] >= $validated['prix_total']) {
                    $reservation->update(['statut' => 'confirmee']);
                }
            }

            DB::commit();

            return redirect()->route('admin.reservations.show', $reservation->id)
                ->with('success', 'La réservation a été créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage())->withInput();
        }
    }


    public function show(Reservation $reservation)
    {
        $reservation->load([
            'client.user',
            'details.chambre.propriete',
            'details.chambre.medias',
            'details.chambre.equipements',
            'paiements',
        ]);

        // Vérifier si la réservation est payée
        $dernierPaiement = $reservation->paiements()
            ->where('statut', 'valide')
            ->latest()
            ->first();

        $estPayee = $dernierPaiement !== null;

        // Générer le QR code si la réservation est payée et non annulée
        $qrCodeBase64 = null;
        if ($estPayee && $reservation->statut !== 'annulee') {
            $qrData = route('reservations.qr-display', [
                'reference' => $reservation->reference
            ]);

            try {
                $options = new QROptions([
                    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel' => QRCode::ECC_H,
                    'scale' => 5,
                    'imageBase64' => false
                ]);

                $qrImage = (new QRCode($options))->render($qrData);
                $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrImage);
            } catch (\Exception $e) {
                Log::error('Erreur lors de la génération du QR code : ' . $e->getMessage());
            }
        }

        $communications = Communication::where('reservation_id', $reservation->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reservations.show', compact('reservation', 'communications', 'qrCodeBase64', 'estPayee'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente_paiement,acompte_paye,acompte_payé,confirmee,annulee,terminee',
            'notes_admin' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $oldStatut = $reservation->statut;
            $statut = str_replace('é', 'e', $validated['statut']);
            $reservation->update([
                'statut' => $statut,
                'notes_admin' => $validated['notes_admin'],
            ]);

            $this->logAction('update_status', $reservation, [
                'old_statut' => $oldStatut,
                'new_statut' => $validated['statut'],
                'notes' => $validated['notes_admin'],
            ]);

            // Envoyer une notification automatique au client (tentative)
            try {
                $message = "Votre réservation {$reservation->reference} a été mise à jour au statut : {$validated['statut']}.";
                if ($validated['notes_admin']) {
                    $message .= " Notes : {$validated['notes_admin']}";
                }
                $this->notificationService->sendNotification(
                    $reservation->client->user,
                    $reservation,
                    'email',
                    'Mise à jour de votre réservation',
                    $message
                );
            } catch (\Exception $e) {
                Log::warning('Échec de l\'envoi de notification email lors de la mise à jour du statut', [
                    'reservation_id' => $reservation->id,
                    'error' => $e->getMessage()
                ]);
            }

            DB::commit();
            return redirect()->route('admin.reservations.show', $reservation->id)
                ->with('success', 'Statut de la réservation mis à jour.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la mise à jour du statut', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'reservation_ids' => 'required|array',
            'reservation_ids.*' => 'exists:reservations,id',
            'statut' => 'required|in:en_attente_paiement,acompte_paye,acompte_payé,confirmee,annulee,terminee',
        ]);

        DB::beginTransaction();
        try {
            $reservations = Reservation::whereIn('id', $request->reservation_ids)->get();
            $statut = str_replace('é', 'e', $request->statut);
            foreach ($reservations as $reservation) {
                $oldStatut = $reservation->statut;
                $reservation->update([
                    'statut' => $statut,
                    'id_admin_modification' => Auth::id(),
                    'date_modification' => now(),
                ]);

                $this->logAction('bulk_update_status', $reservation, [
                    'old_statut' => $oldStatut,
                    'new_statut' => $request->statut,
                ]);

                // Envoyer une notification automatique au client
                $message = "Votre réservation {$reservation->reference} a été mise à jour au statut : {$request->statut}.";
                $this->notificationService->sendNotification(
                    $reservation->client->user,
                    $reservation,
                    'email',
                    'Mise à jour de votre réservation',
                    $message
                );
            }

            DB::commit();
            return redirect()->route('admin.reservations.index')
                ->with('success', 'Réservations mises à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la mise à jour groupée', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function paiements(Request $request)
    {
        $statut = $request->query('statut');
        $dateDebut = $request->query('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->query('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

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

        $paiements = $query->paginate(10);

        $statistiques = [
            'total' => Paiement::where('statut', 'valide')->sum('montant'),
            'en_attente' => Paiement::where('statut', 'en_attente')->count(),
            'refuse' => Paiement::where('statut', 'refuse')->count(),
        ];

        return view('admin.paiements.index', compact('paiements', 'statistiques', 'dateDebut', 'dateFin'));
    }


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


    public function manageAvailability(Request $request, Chambre $chambre)
    {
        $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            'raison' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $indisponibilite = $chambre->periodesIndisponibilite()->create([
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'raison' => $request->raison,
                'id_admin' => Auth::id(),
            ]);

            $this->logAction('add_indisponibilite', $chambre, [
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'raison' => $request->raison,
            ]);

            DB::commit();
            return redirect()->route('admin.chambres.show', $chambre->id)
                ->with('success', 'Période d\'indisponibilité ajoutée.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'ajout d\'indisponibilité', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function appartement(Request $request)
    {
        $query = Chambre::with(['propriete', 'typeChambre', 'equipements'])
            ->orderBy('created_at', 'desc');

        if ($request->query('propriete_id')) {
            $query->where('id_propriete', $request->query('propriete_id'));
        }

        if ($request->query('search')) {
            $search = $request->query('search');
            $query->where('numero_chambre', 'like', "%{$search}%");
        }

        $appartement = $query->paginate(10);
        $proprietes = Propriete::pluck('nom', 'id')->toArray();

        return view('admin.chambres.index', compact('appartement', 'proprietes'));
    }

    public function storeChambre(Request $request)
    {
        $request->validate([
            'numero_chambre' => 'required|string|max:50|unique:appartement',
            'id_propriete' => 'required|exists:proprietes,id',
            'id_type_chambre' => 'required|exists:type_appartement,id',
            'prix_base' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $chambre = Chambre::create($request->all());

            $this->logAction('create_chambre', $chambre, $request->all());

            DB::commit();
            return redirect()->route('admin.chambres.index')
                ->with('success', 'Chambre ajoutée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création de la chambre', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function addEquipement(Request $request, Chambre $chambre)
    {
        $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
        ]);

        DB::beginTransaction();
        try {
            if (!$chambre->equipements()->where('equipement_id', $request->equipement_id)->exists()) {
                $chambre->equipements()->attach($request->equipement_id);

                $this->logAction('add_equipement', $chambre, [
                    'equipement_id' => $request->equipement_id,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.chambres.show', $chambre->id)
                ->with('success', 'Équipement ajouté.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'ajout d\'équipement', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function notifyClient(Request $request, Reservation $reservation, NotificationService $notificationService)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'type' => 'required|in:email,sms',
        ]);

        DB::beginTransaction();
        try {
            // Utiliser $notificationService au lieu de $this->notificationService
            $notificationService->sendNotification(
                $reservation->client->user,
                $reservation,
                $request->type,
                'Notification de réservation',
                $request->message
            );
            // Enregistrer dans l'historique des communications
            $communication = Communication::create([
                'reservation_id' => $reservation->id,
                'type' => $request->type,
                'message' => $request->message,
                'admin_id' => Auth::id(),
                'sent_at' => now(),
            ]);

            $this->logAction('notify_client', $reservation, [
                'type' => $request->type,
                'message' => $request->message,
            ]);

            DB::commit();
            return redirect()->route('admin.reservations.show', $reservation->id)
                ->with('success', 'Notification envoyée.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'envoi de la notification', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }



    public function communicationHistory(Reservation $reservation)
    {
        $communications = Communication::where('reservation_id', $reservation->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reservations.communications', compact('reservation', 'communications'));
    }

    public function rapport(Request $request)
    {
        $debut = $request->get('date_debut', Carbon::now()->startOfMonth());
        $fin = $request->get('date_fin', Carbon::now()->endOfMonth());

        $reservations = Reservation::whereBetween('created_at', [$debut, $fin])
            ->with(['client.user', 'details.chambre.propriete'])
            ->get();

        $statistiques = [
            'total' => $reservations->count(),
            'revenu' => $reservations->sum('prix_total'),
            'par_statut' => $reservations->groupBy('statut')->map->count(),
            'par_propriete' => $reservations->groupBy('details.chambre.id_propriete')
                ->map->sum('prix_total'),
        ];

        return view('admin.reservations.rapport', compact('reservations', 'statistiques', 'debut', 'fin'));
    }

    // public function demandesVisite(Request $request)
    // {
    //     $statut = $request->query('statut');
    //     $dateDebut = $request->query('date_debut', Carbon::now()->startOfWeek()->format('Y-m-d'));
    //     $dateFin = $request->query('date_fin', Carbon::now()->endOfWeek()->format('Y-m-d'));

    //     $query = DemandeVisite::with(['client.user', 'chambre.propriete'])
    //         ->orderBy('date_demande', 'desc');

    //     if ($statut) {
    //         $query->where('statut', $statut);
    //     }

    //     if ($dateDebut) {
    //         $query->whereDate('date_demande', '>=', $dateDebut);
    //     }

    //     if ($dateFin) {
    //         $query->whereDate('date_demande', '<=', $dateFin);
    //     }

    //     $demandes = $query->paginate(10);

    //     return view('admin.demandes-visite.index', compact('demandes', 'dateDebut', 'dateFin'));
    // }

    /**
     * Affiche la liste des demandes de visite avec filtres
     */
    public function demandesVisite(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $statut = $request->query('statut');
        $dateDebut = $request->query('date_debut', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $dateFin = $request->query('date_fin', Carbon::now()->endOfWeek()->format('Y-m-d'));

        // Construire la requête avec les relations nécessaires
        $query = DemandeVisite::with([
            'client.user' => function ($query) {
                $query->select('id', 'name', 'prenom', 'email', 'telephone');
            },
            'chambre.propriete'
        ])->orderBy('date_demande', 'desc');
        // dd($query);
        // Appliquer les filtres
        if ($statut) {
            $query->where('statut', $statut);
        }

        if ($dateDebut) {
            $query->whereDate('date_demande', '>=', $dateDebut);
        }

        if ($dateFin) {
            $query->whereDate('date_demande', '<=', $dateFin);
        }

        // Statistiques pour le tableau de bord
        $stats = [
            'en_attente' => DemandeVisite::where('statut', 'en_attente')->count(),
            'aujourdhui' => DemandeVisite::whereDate('date_visite_souhaitee', today())->count(),
            'cette_semaine' => DemandeVisite::whereBetween('date_visite_souhaitee', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        $demandes = $query->paginate(10);

        // Passer les données à la vue
        return view('admin.demandes-visite.index', compact('demandes', 'dateDebut', 'dateFin', 'stats'));
    }
    public function scheduleVisite(Request $request, DemandeVisite $demande)
    {
        $request->validate([
            'date_visite' => 'required|date|after_or_equal:today',
            'heure_visite' => 'required',
            'notes_admin' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $demande->update([
                'date_confirmation' => $request->date_visite,
                'heure_debut' => $request->heure_visite,
                'statut' => 'programmee',
                'notes_admin' => $request->notes_admin,
                'id_admin_confirmation' => Auth::id(),
            ]);

            $this->logAction('schedule_visite', $demande, [
                'date_visite' => $request->date_visite,
                'heure_visite' => $request->heure_visite,
            ]);

            // Envoyer une notification automatique au client
            $message = "Votre demande de visite pour l'appartement {$demande->chambre->numero_chambre} a été programmée pour le {$request->date_visite} à {$request->heure_visite}.";
            if ($request->notes_admin) {
                $message .= " Notes : {$request->notes_admin}";
            }
            $this->notificationService->sendNotification(
                $demande->client->user,
                null,
                'visite',
                'Visite programmée',
                $message
            );

            DB::commit();
            return redirect()->route('admin.demandes-visite.index')
                ->with('success', 'Visite programmée.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la programmation de la visite', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function confirmVisite(Request $request, DemandeVisite $demande)
    {
        $request->validate([
            'date_confirmation' => 'required|date',
            'notes_admin' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $demande->update([
                'date_confirmation' => $request->date_confirmation,
                'statut' => 'confirmee',
                'notes_admin' => $request->notes_admin,
                'id_admin_confirmation' => Auth::id(),
            ]);

            $this->logAction('confirm_visite', $demande, [
                'date_confirmation' => $request->date_confirmation,
            ]);

            $message = "Votre demande de visite pour l'appartement {$demande->chambre->numero_chambre} a été confirmée pour le " . Carbon::parse($request->date_confirmation)->format('d/m/Y à H:i') . ".";
            if ($request->notes_admin) {
                $message .= " Notes : {$request->notes_admin}";
            }

            $this->notificationService->sendNotification(
                $demande->client->user,
                null,
                'visite',
                'Visite confirmée',
                $message
            );

            DB::commit();
            return redirect()->route('admin.demandes-visite.index')
                ->with('success', 'Visite confirmée et notifiée au client.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la confirmation de la visite', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }



    public function rejectVisite(Request $request, DemandeVisite $demande)
    {
        if (!Auth::check()) {
            Log::error('Utilisateur non authentifié lors du rejet de la demande', ['demande_id' => $demande->id]);
            return back()->with('error', 'Erreur : Utilisateur non authentifié.');
        }

        Log::info('Début du rejet de la demande', ['demande_id' => $demande->id]);

        $request->validate([
            'notes_admin' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $demande->update([
                'statut' => 'annulee',
                'id_admin_confirmation' => Auth::id(),
                'notes_admin' => $request->notes_admin,
                'date_confirmation' => now(),
            ]);

            $this->logAction('reject_visite', $demande, [
                'details_refus' => $request->notes_admin,
            ]);

            $message = "Votre demande de visite pour l'appartement {$demande->chambre->typeChambre->nom} a malheureusement été refusée.";
            if ($request->notes_admin) {
                $message .= " Raison : " . $request->notes_admin;
            }

            $this->notificationService->sendNotification(
                $demande->client->user,
                null,
                'visite',
                'Demande de visite refusée',
                $message
            );

            DB::commit();
            return redirect()->route('admin.demandes-visite.index')
                ->with('success', 'Demande de visite refusée.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors du refus de la visite', [
                'demande_id' => $demande->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    // private function calculerTauxOccupation()
    // {
    //     $totalappartement = Chambre::count();
    //     $reservationsActives = Reservation::where('statut', 'confirmee')
    //         ->where('date_arrivee', '<=', now())
    //         ->where('date_depart', '>=', now())
    //         ->count();

    //     return $totalappartement > 0 ? ($reservationsActives / $totalappartement) * 100 : 0;
    // }

    public function manageAdmins(Request $request)
    {
        $users = User::where('type_utilisateur', 'admin')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateAdminRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:super_admin,property_manager,support',
        ]);

        DB::beginTransaction();
        try {
            $oldRole = $user->role;
            $user->update(['role' => $request->role]);

            $this->logAction('update_admin_role', $user, [
                'old_role' => $oldRole,
                'new_role' => $request->role,
            ]);

            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', 'Rôle mis à jour.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la mise à jour du rôle', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Enregistre un paiement manuel pour une réservation
     */
    public function storePayment(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric|min:0',
            'methode_paiement' => 'required|string|in:especes,virement,carte_credit,mobile_money,autre',
            'reference_transaction' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $paiement = Paiement::create([
                'id_reservation' => $reservation->id,
                'montant' => $validated['montant'],
                'date_paiement' => now(),
                'methode_paiement' => $validated['methode_paiement'],
                'reference_transaction' => $validated['reference_transaction'] ?? 'MANUAL-' . strtoupper(uniqid()),
                'statut' => 'valide',
                'id_admin_validation' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Mettre à jour l'acompte payé de la réservation
            $nouveauTotalPaye = $reservation->acompte_paye + $validated['montant'];

            // Si le total payé atteint ou dépasse le prix total, on peut marquer comme terminée ou confirmée
            $nouveauStatut = $reservation->statut;
            if ($nouveauTotalPaye >= $reservation->prix_total) {
                $nouveauStatut = 'confirmee';
            }

            $reservation->update([
                'acompte_paye' => $nouveauTotalPaye,
                'statut' => $nouveauStatut,
            ]);

            $this->logAction('manual_payment_recorded', $reservation, [
                'paiement_id' => $paiement->id,
                'montant' => $validated['montant'],
                'methode' => $validated['methode_paiement'],
            ]);

            // Notifier le client du paiement reçu (tentative)
            try {
                $this->notificationService->sendNotification(
                    $reservation->client->user,
                    $reservation,
                    'email',
                    'Paiement reçu',
                    "Nous avons bien reçu votre paiement de {$validated['montant']} FCFA par {$validated['methode_paiement']} pour la réservation {$reservation->reference}."
                );
            } catch (\Exception $e) {
                Log::warning('Échec de l\'envoi de notification email lors de l\'enregistrement d\'un paiement manuel', [
                    'reservation_id' => $reservation->id,
                    'error' => $e->getMessage()
                ]);
            }

            DB::commit();
            return redirect()->route('admin.reservations.show', $reservation->id)
                ->with('success', 'Paiement enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'enregistrement du paiement manuel', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function calculerTauxOccupation($dateDebut = null, $dateFin = null)
    {
        $dateDebut = $dateDebut ?? Carbon::now()->startOfDay();
        $dateFin = $dateFin ?? Carbon::now()->endOfDay();

        $totalappartement = Chambre::count();
        $jours = Carbon::parse($dateDebut)->diffInDays(Carbon::parse($dateFin)) + 1;
        $appartementDisponibles = $totalappartement * $jours;

        $joursOccupes = Reservation::where('statut', 'confirmee')
            ->where('date_arrivee', '<=', $dateFin)
            ->where('date_depart', '>=', $dateDebut)
            ->get()
            ->sum(function ($reservation) use ($dateDebut, $dateFin) {
                $debut = Carbon::parse($reservation->date_arrivee)->max(Carbon::parse($dateDebut));
                $fin = Carbon::parse($reservation->date_depart)->min(Carbon::parse($dateFin));
                $jours = $debut->diffInDays($fin) + 1;
                return $jours > 0 ? $jours : 0;
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
}
