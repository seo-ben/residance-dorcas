<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crée une session Stripe Checkout pour une réservation.
     */
    public function createCheckoutSession(Reservation $reservation)
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'xof',
                    'product_data' => [
                        'name' => 'Réservation ' . $reservation->reference,
                        'description' => 'Séjour du ' . Carbon::parse($reservation->date_arrivee)->format('d/m/Y') . ' au ' . Carbon::parse($reservation->date_depart)->format('d/m/Y'),
                    ],
                    'unit_amount' => intval($reservation->prix_total),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('reservations.payment.success', ['reservation' => $reservation->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('reservations.payment.cancel', ['reservation' => $reservation->id]),
            'client_reference_id' => (string) $reservation->id,
            'expires_at' => now()->addMinutes(30)->timestamp,
            'metadata' => [
                'reservation_id' => $reservation->id,
                'client_id' => $reservation->id_client,
            ],
        ]);
    }

    /**
     * Traite le succès d'un paiement après retour de Stripe.
     */
    public function processSuccessfulPayment(Reservation $reservation, $sessionId)
    {
        $session = Session::retrieve($sessionId);

        if ($session->client_reference_id !== (string)$reservation->id) {
            throw new \Exception('Session Stripe non valide pour cette réservation.');
        }

        if ($session->payment_status !== 'paid') {
            throw new \Exception('Le paiement n\'a pas été effectué.');
        }

        return DB::transaction(function () use ($reservation, $session) {
            // Éviter les doublons
            $existingPayment = Paiement::where('reference_transaction', $session->payment_intent)->first();
            if ($existingPayment) return $existingPayment;

            $paiement = Paiement::create([
                'id_reservation' => $reservation->id,
                'montant' => $reservation->prix_total,
                'date_paiement' => now(),
                'methode_paiement' => 'carte_credit',
                'reference_transaction' => $session->payment_intent,
                'statut' => 'valide',
                'notes' => 'Paiement via Stripe Checkout - Référence : ' . $session->payment_intent,
            ]);

            $reservation->update([
                'statut' => 'terminee',
                'acompte_paye' => $reservation->prix_total
            ]);

            return $paiement;
        });
    }

    /**
     * Gère les événements Webhook de Stripe.
     */
    public function handleWebhook($payload, $signature)
    {
        $endpointSecret = config('services.stripe.webhook_secret');
        $event = Webhook::constructEvent($payload, $signature, $endpointSecret);

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                if ($session->payment_status === 'paid') {
                    $reservation = Reservation::find($session->client_reference_id);
                    if ($reservation) {
                        $this->processSuccessfulPayment($reservation, $session->id);
                    }
                }
                break;

            case 'checkout.session.expired':
                $session = $event->data->object;
                $reservation = Reservation::find($session->client_reference_id);
                if ($reservation && $reservation->statut === 'en_attente_paiement') {
                    $reservation->update(['statut' => 'expiree']);
                }
                break;
        }

        return true;
    }
}
