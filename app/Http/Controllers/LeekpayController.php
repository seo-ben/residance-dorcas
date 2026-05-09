<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeekpayService;
use App\Models\Reservation;
use App\Models\DemandeVisite;

class LeekpayController extends Controller
{
    protected $leekpayService;

    public function __construct(LeekpayService $leekpayService)
    {
        $this->leekpayService = $leekpayService;
    }

    /**
     * Redirige l'utilisateur vers Leekpay pour une réservation
     */
    public function initiateReservationPayment($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        $result = $this->leekpayService->createPaymentLink(
            $reservation->prix_total,
            "Réservation d'appartement #" . $reservation->reference,
            $reservation->id
        );

        if ($result['success']) {
            return redirect($result['url']);
        }

        return back()->with('error', $result['error']);
    }

    /**
     * Redirige l'utilisateur vers Leekpay pour les frais de visite
     */
    public function initiateVisitPayment($id)
    {
        $demande = DemandeVisite::with('chambre')->findOrFail($id);
        $frais = $demande->chambre->frais_visite ?? 5000;

        $result = $this->leekpayService->createPaymentLink(
            $frais,
            "Frais de visite - Apt. " . $demande->chambre->numero_chambre,
            null,
            $demande->id
        );

        if ($result['success']) {
            return redirect($result['url']);
        }

        return back()->with('error', $result['error']);
    }

    /**
     * Callback de succès (GET)
     */
    public function success(Request $request)
    {
        if ($request->has('reservation_id')) {
            return redirect()->route('reservations.confirmation', $request->reservation_id)
                             ->with('success', 'Paiement effectué avec succès !');
        }
        
        return redirect()->route('client.visites.index')
                         ->with('success', 'Frais de visite réglés avec succès !');
    }

    /**
     * Callback d'annulation (GET)
     */
    public function cancel()
    {
        return redirect()->route('chambres.index')
                         ->with('warning', 'Le paiement a été annulé.');
    }

    /**
     * Webhook (POST) - Notification directe de serveur à serveur
     */
    public function webhook(Request $request)
    {
        $success = $this->leekpayService->handleWebhook($request);
        
        if ($success) {
            return response()->json(['status' => 'processed']);
        }

        return response()->json(['status' => 'ignored'], 400);
    }
}
