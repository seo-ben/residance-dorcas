<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\DemandeVisite;
use App\Models\Equipement;
use App\Models\Paiement;
use App\Models\Propriete;
use App\Models\Reservation;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Communication;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationNotificationMail;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Refund;

class AdminReservationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->notificationService = $notificationService;
    }


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

        if ($statut) {
            $query->where('statut', $statut);
        }

        if ($dateDebut) {
            $query->whereDate('date_arrivee', '>=', $dateDebut);
        }

        if ($dateFin) {
            $query->whereDate('date_depart', '<=', $dateFin);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('client.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($proprieteId) {
            $query->whereHas('details.chambre', function ($q) use ($proprieteId) {
                $q->where('id_propriete', $proprieteId);
            });
        }

        $reservations = $query->orderBy($sortBy, $sortDirection)->paginate(10);
        $proprietes = Propriete::pluck('nom', 'id')->toArray();

        return view('admin.reservations.index', compact('reservations', 'dateDebut', 'dateFin', 'proprietes'));
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

        $communications = Communication::where('reservation_id', $reservation->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reservations.show', compact('reservation', 'communications'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente_paiement,acompte_payé,confirmee,annulee,terminee',
            'notes_admin' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $oldStatut = $reservation->statut;
            $reservation->update([
                'statut' => $validated['statut'],
                'notes_admin' => $validated['notes_admin'],
                'id_admin_modification' => Auth::id(),
                'date_modification' => now(),
            ]);

            $this->logAction('update_status', $reservation, [
                'old_statut' => $oldStatut,
                'new_statut' => $validated['statut'],
                'notes' => $validated['notes_admin'],
            ]);

            // Envoyer une notification automatique au client
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
            'statut' => 'required|in:en_attente_paiement,acompte_payé,confirmee,annulee,terminee',
        ]);

        DB::beginTransaction();
        try {
            $reservations = Reservation::whereIn('id', $request->reservation_ids)->get();
            foreach ($reservations as $reservation) {
                $oldStatut = $reservation->statut;
                $reservation->update([
                    'statut' => $request->statut,
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
            'montant' => 'required|numeric|min:1|max:' . $paiement->montant,
            'raison' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $refund = Refund::create([
                'charge' => $paiement->reference_transaction,
                'amount' => intval($request->montant),
                'reason' => $request->raison,
            ]);

            $paiement->update([
                'statut' => 'rembourse',
                'notes' => $paiement->notes . "\nRemboursement de {$request->montant} F CFA le " . now() . " : {$request->raison}",
            ]);

            $this->logAction('refund', $paiement, [
                'montant' => $request->montant,
                'raison' => $request->raison,
            ]);

            // Envoyer une notification automatique au client
            $message = "Un remboursement de {$request->montant} F CFA a été effectué pour votre réservation {$paiement->reservation->reference}. Raison : {$request->raison}.";
            $this->notificationService->sendNotification(
                $paiement->reservation->client->user,
                $paiement->reservation,
                'email',
                'Remboursement effectué',
                $message
            );

            DB::commit();
            return redirect()->route('admin.reservations.show', $paiement->reservation_id)
                ->with('success', 'Remboursement effectué avec succès.');
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

    public function notifyClient(Request $request, Reservation $reservation)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'type' => 'required|in:email,sms',
        ]);

        DB::beginTransaction();
        try {
            // Envoyer la notification via le service
            $this->notificationService->sendNotification(
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

    public function demandesVisite(Request $request)
    {
        $statut = $request->query('statut');
        $dateDebut = $request->query('date_debut', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $dateFin = $request->query('date_fin', Carbon::now()->endOfWeek()->format('Y-m-d'));

        $query = DemandeVisite::with(['client.user', 'chambre.propriete'])
            ->orderBy('date_demande', 'desc');

        if ($statut) {
            $query->where('statut', $statut);
        }

        if ($dateDebut) {
            $query->whereDate('date_demande', '>=', $dateDebut);
        }

        if ($dateFin) {
            $query->whereDate('date_demande', '<=', $dateFin);
        }

        $demandes = $query->paginate(10);

        return view('admin.demandes-visite.index', compact('demandes', 'dateDebut', 'dateFin'));
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
            $message = "Votre demande de visite pour la chambre {$demande->chambre->numero_chambre} a été programmée pour le {$request->date_visite} à {$request->heure_visite}.";
            if ($request->notes_admin) {
                $message .= " Notes : {$request->notes_admin}";
            }
            $this->notificationService->sendNotification(
                $demande->client->user,
                $demande->reservation,
                'email',
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

    public function rejectVisite(Request $request, DemandeVisite $demande)
    {
        $request->validate([
            'details_refus' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $demande->update([
                'statut' => 'refusee',
                'id_admin_confirmation' => Auth::id(),
                'details_admin' => $request->details_refus,
                'date_confirmation' => now(),
            ]);

            $this->logAction('reject_visite', $demande, [
                'details_refus' => $request->details_refus,
            ]);

            // Envoyer une notification automatique au client
            $message = "Votre demande de visite pour la chambre {$demande->chambre->numero_chambre} a été refusée. Raison : {$request->details_refus}.";
            $this->notificationService->sendNotification(
                $demande->client->user,
                $demande->reservation,
                'email',
                'Demande de visite refusée',
                $message
            );

            DB::commit();
            return redirect()->route('admin.demandes-visite.index')
                ->with('success', 'Demande de visite refusée.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors du refus de la visite', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function manageAdmins(Request $request)
    {
        $users = User::where('role', 'admin')->paginate(10);
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

    private function calculerTauxOccupation()
    {
        $totalappartement = Chambre::count();
        $reservationsActives = Reservation::where('statut', 'confirmee')
            ->where('date_arrivee', '<=', now())
            ->where('date_depart', '>=', now())
            ->count();

        return $totalappartement > 0 ? ($reservationsActives / $totalappartement) * 100 : 0;
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
