<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\Client;
use App\Models\DemandeVisite;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Services\LeekpayService;

class ReservationController extends Controller
{
    protected $bookingService;
    protected $paymentService;
    protected $leekpayService;

    public function __construct(
        BookingService $bookingService, 
        PaymentService $paymentService,
        LeekpayService $leekpayService
    ) {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
        $this->leekpayService = $leekpayService;
    }

    /**
     * @group Client - Réservations
     * Liste des réservations du client
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Récupérer les IDs de clients liés par id_utilisateur
        $clientIds = Client::where('id_utilisateur', $user->id)->pluck('id')->toArray();

        // 2. Fallback: Chercher par email si nécessaire
        $clientByEmail = Client::whereHas('user', function($q) use ($user) {
            $q->where('email', $user->email);
        })->pluck('id')->toArray();
        
        $allClientIds = array_unique(array_merge($clientIds, $clientByEmail));

        if (empty($allClientIds)) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $reservations = Reservation::with(['details.chambre.typeChambre', 'details.chambre.propriete', 'details.chambre.medias'])
            ->whereIn('id_client', $allClientIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'debug_search_info' => [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'client_ids_found' => $allClientIds,
                'search_method' => 'ID + Email Fallback',
                'count' => $reservations->count()
            ],
            'data' => $reservations
        ]);
    }

    /**
     * @group Client - Réservations
     * Créer ou mettre à jour une réservation (brouillon ou en attente)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'chambre_id' => 'required|exists:appartement,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee',
            'visite_id' => 'nullable|exists:demande_visites,id',
            'notes' => 'nullable|string',
            'reservation_id' => 'nullable|exists:reservations,id',
            'save_draft' => 'boolean'
        ]);

        $user = Auth::user();
        $client = $user->client;

        if (!$client) {
            $client = \App\Models\Client::create([
                'id_utilisateur' => $user->id,
                'points_fidelite' => 0
            ]);
        }

        $reservation = isset($data['reservation_id']) ? Reservation::findOrFail($data['reservation_id']) : null;
        if ($reservation && $reservation->id_client !== $client->id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        // Vérification disponibilité
        if (!$this->bookingService->checkAvailability($data['chambre_id'], $data['date_arrivee'], $data['date_depart'], $reservation ? $reservation->id : null)) {
            $conflicts = $this->bookingService->getConflictingReservations($data['chambre_id'], $data['date_arrivee'], $data['date_depart'], $reservation ? $reservation->id : null);
            $suggestions = $this->bookingService->getAvailablePeriods($data['chambre_id']);
            
            return response()->json([
                'success' => false,
                'message' => 'L\'appartement n\'est plus disponible pour ces dates.',
                'conflicts' => $conflicts,
                'suggestions' => collect($suggestions)->take(3) // On renvoie les 3 prochaines périodes
            ], 422);
        }

        try {
            $data['statut'] = $request->input('save_draft', false) ? 'brouillon' : 'en_attente_paiement';
            $reservation = $this->bookingService->saveReservation($data, $reservation);

            return response()->json([
                'success' => true,
                'message' => $data['statut'] === 'brouillon' ? 'Brouillon enregistré.' : 'Réservation créée, en attente de paiement.',
                'data' => $reservation->load(['details.chambre.typeChambre', 'details.chambre.propriete'])
            ]);
        } catch (\Exception $e) {
            \Log::error("Erreur API create reservation: " . $e->getMessage(), [
                'user_id' => Auth::id(),
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de la réservation : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @group Client - Réservations
     * Détails d'une réservation
     */
    public function show($id)
    {
        $client = Auth::user()->client;
        if (!$client) return response()->json(['message' => 'Profil client non trouvé.'], 404);

        $reservation = Reservation::with(['details.chambre.typeChambre', 'details.chambre.propriete', 'paiements', 'client.user'])
            ->where('id_client', $client->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $reservation
        ]);
    }

    /**
     * @group Client - Réservations
     * Annuler une réservation
     */
    public function cancel($id)
    {
        $client = Auth::user()->client;
        if (!$client) return response()->json(['message' => 'Profil client non trouvé.'], 404);

        $reservation = Reservation::where('id_client', $client->id)->findOrFail($id);

        if (!$reservation->isPeutEtreAnnulee()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette réservation ne peut plus être annulée.'
            ], 422);
        }

        $reservation->update(['statut' => 'annulee']);

        return response()->json([
            'success' => true,
            'message' => 'Réservation annulée avec succès.'
        ]);
    }

    /**
     * @group Client - Réservations
     * Obtenir le lien de paiement pour une réservation
     */
    public function getPaymentLink($id)
    {
        $client = Auth::user()->client;
        $reservation = Reservation::where('id_client', $client->id)->findOrFail($id);

        if ($reservation->isPayee()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette réservation est déjà payée.'
            ], 422);
        }

        $result = $this->leekpayService->createPaymentLink(
            $reservation->prix_total,
            "Réservation #" . $reservation->reference,
            $reservation->id
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'payment_url' => $result['url']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error']
        ], 500);
    }
}
