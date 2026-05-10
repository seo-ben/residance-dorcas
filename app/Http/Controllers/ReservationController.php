<?php

namespace App\Http\Controllers;

use App\Models\DemandeVisite;
use App\Models\Reservation;
use App\Models\Chambre;
use App\Models\Paiement;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    protected $bookingService;
    protected $paymentService;
    protected $auditService;

    public function __construct(
        BookingService $bookingService,
        PaymentService $paymentService,
        AuditService $auditService
    ) {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
        $this->auditService = $auditService;
    }

    public function index()
    {
        $client = auth()->user()->client;

        if (!$client) {
            return view('reservations.index', ['reservations' => collect()]);
        }

        $reservations = Reservation::with(['details.chambre.typeChambre', 'details.chambre.propriete', 'details.chambre.medias'])
            ->where('id_client', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        if ($request->has('reservation_id')) {
            $reservation = Reservation::with(['details.chambre'])->findOrFail($request->reservation_id);
            if ($reservation->id_client !== optional(Auth::user()->client)->id) abort(403);
            if (!$reservation->isPeutEtreModifiee()) {
                return redirect()->route('reservations.index')->with('error', 'Modification impossible.');
            }

            $chambre = $reservation->details->first()->chambre;
            $pricing = $this->bookingService->calculatePrice($chambre, $reservation->date_arrivee, $reservation->date_depart);

            return view('reservations.create', array_merge($pricing, [
                'chambre' => $chambre,
                'reservation' => $reservation,
                'isContinuing' => true,
                'dateArrivee' => $reservation->date_arrivee,
                'dateDepart' => $reservation->date_depart,
            ]));
        }

        $chambre_id = $request->get('chambre_id');
        if (!$chambre_id) return redirect()->route('chambres.index')->with('error', 'Sélectionnez une chambre.');

        $chambre = Chambre::findOrFail($chambre_id);
        $demandeVisite = $request->get('visite_id') ? DemandeVisite::find($request->visite_id) : null;

        $dateArrivee = $request->get('date_arrivee') ?? $request->cookie('search_date_arrivee') ?? Carbon::now()->addDays(1)->format('Y-m-d');
        $dateDepart = $request->get('date_depart') ?? $request->cookie('search_date_depart') ?? Carbon::now()->addDays(2)->format('Y-m-d');

        if (!$this->bookingService->checkAvailability($chambre->id, $dateArrivee, $dateDepart)) {
            $periodesDisponibles = $this->bookingService->getAvailablePeriods($chambre->id);
            return view('reservations.create', compact('chambre', 'demandeVisite', 'dateArrivee', 'dateDepart', 'periodesDisponibles'))
                ->with('warning', 'Indisponible pour ces dates.');
        }

        $pricing = $this->bookingService->calculatePrice($chambre, $dateArrivee, $dateDepart);

        return view('reservations.create', array_merge($pricing, [
            'chambre' => $chambre,
            'demandeVisite' => $demandeVisite,
            'dateArrivee' => $dateArrivee,
            'dateDepart' => $dateDepart,
            'isContinuing' => false
        ]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'chambre_id' => 'required|exists:appartement,id',
            'date_arrivee' => 'required|date|after_or_equal:today',
            'date_depart' => 'required|date|after:date_arrivee',
            'visite_id' => 'nullable|exists:demande_visites,id',
            'notes' => 'nullable|string',
            'reservation_id' => 'nullable|exists:reservations,id',
        ]);

        $reservation = isset($data['reservation_id']) ? Reservation::findOrFail($data['reservation_id']) : null;
        if ($reservation && $reservation->id_client !== optional(Auth::user()->client)->id) abort(403);

        $data['statut'] = $request->has('save_draft') ? 'brouillon' : 'en_attente_paiement';
        $reservation = $this->bookingService->saveReservation($data, $reservation);

        return $request->has('save_draft')
            ? redirect()->route('reservations.index')->with('success', 'Brouillon enregistré.')
            : redirect()->route('paiement.leekpay.initiate.reservation', $reservation->id);
    }

    public function payment($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->id_client !== optional(Auth::user()->client)->id) abort(403);
        if ($reservation->isPayee()) return redirect()->route('reservations.index')->with('error', 'Déjà payée.');

        return redirect()->route('paiement.leekpay.initiate.reservation', $reservation->id);
    }

    public function paymentSuccess(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->id_client !== optional(Auth::user()->client)->id) abort(403);

        try {
            $this->paymentService->processSuccessfulPayment($reservation, $request->session_id);
            return redirect()->route('reservations.confirmation', $reservation->id)->with('success', 'Paiement réussi !');
        } catch (\Exception $e) {
            return redirect()->route('reservations.index')->with('error', $e->getMessage());
        }
    }

    public function paymentCancel($id)
    {
        return redirect()->route('reservations.index')->with('warning', 'Paiement annulé. La réservation reste en attente.');
    }

    public function handleWebhook(Request $request)
    {
        try {
            $this->paymentService->handleWebhook($request->getContent(), $request->header('Stripe-Signature'));
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function confirmation($id)
    {
        $reservation = Reservation::with(['details.chambre.typeChambre', 'details.chambre.propriete', 'paiements'])->findOrFail($id);
        if ($reservation->id_client !== optional(Auth::user()->client)->id) abort(403);
        if (!$reservation->isConfirmee()) return redirect()->route('reservations.index');

        $paiement = $reservation->paiements()->where('statut', 'valide')->latest()->first();
        $qrCodeBase64 = $this->generateQR(route('reservations.qr-display', ['reference' => $reservation->reference]));

        return view('reservations.confirmation', compact('reservation', 'paiement', 'qrCodeBase64'));
    }

    public function qrDisplay(Request $request)
    {
        $reservation = Reservation::with(['details.chambre.typeChambre', 'details.chambre.propriete', 'paiements', 'client.user'])
            ->where('reference', $request->get('reference'))->firstOrFail();

        $paiement = $reservation->paiements()->where('statut', 'valide')->latest()->first();
        return view('reservations.qr-display', compact('reservation', 'paiement'));
    }

    public function show($id)
    {
        $reservation = Reservation::with(['details.chambre.typeChambre', 'details.chambre.propriete', 'paiements', 'client.user'])->findOrFail($id);
        if ($reservation->id_client !== optional(Auth::user()->client)->id) abort(403);

        $estPayee = $reservation->isPayee();
        $qrCodeBase64 = $estPayee ? $this->generateQR(route('reservations.qr-display', ['reference' => $reservation->reference])) : null;

        $dateArrivee = Carbon::parse($reservation->date_arrivee);
        $dateDepart = Carbon::parse($reservation->date_depart);
        $aujourdHui = Carbon::now();
        $totalJours = $dateArrivee->diffInDays($dateDepart);
        $joursEcoules = $aujourdHui->isAfter($dateArrivee) ? min($aujourdHui->diffInDays($dateArrivee), $totalJours) : 0;
        $progressionPourcentage = $totalJours > 0 ? ($joursEcoules / $totalJours) * 100 : 0;
        $joursRestants = $aujourdHui->isBefore($dateDepart) ? $aujourdHui->diffInDays($dateDepart) : 0;

        return view('reservations.show', compact('reservation', 'estPayee', 'qrCodeBase64', 'totalJours', 'joursEcoules', 'progressionPourcentage', 'aujourdHui', 'joursRestants'));
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->id_client !== optional(Auth::user()->client)->id) abort(403);

        if (Carbon::now()->diffInDays(Carbon::parse($reservation->date_arrivee), false) < 0) {
            return back()->with('error', 'Annulation impossible (délai dépassé).');
        }

        $reservation->update(['statut' => 'annulee', 'date_depart' => now()]);
        $this->auditService->logAction('reservation_cancelled', $reservation);

        return redirect()->route('reservations.index')->with('success', 'Réservation annulée.');
    }

    public function paiementIndex()
    {
        $paiements = Paiement::with(['reservation.client'])->whereHas('reservation', fn($q) => $q->where('id_client', auth()->id()))
            ->orderBy('date_paiement', 'desc')->paginate(20);

        return view('reservations.paiements.index', compact('paiements'));
    }

    protected function generateQR($data)
    {
        try {
            $options = new QROptions(['outputType' => QRCode::OUTPUT_IMAGE_PNG, 'eccLevel' => QRCode::ECC_H, 'scale' => 5]);
            return 'data:image/png;base64,' . base64_encode((new QRCode($options))->render($data));
        } catch (\Exception $e) {
            return null;
        }
    }
}
