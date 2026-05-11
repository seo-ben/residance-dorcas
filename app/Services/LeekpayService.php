<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\DemandeVisite;
use App\Models\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LeekpayService
{
    protected $apiUrl;
    protected $publicKey;
    protected $privateKey;

    public function __construct()
    {
        // En attendant que les clés soient renseignées dans le .env
        $this->apiUrl = config('services.leekpay.base_url', 'https://api.leekpay.me/v1');
        $this->publicKey = config('services.leekpay.public_key');
        $this->privateKey = config('services.leekpay.private_key');
    }

    /**
     * Crée un lien de paiement dynamique ou une session pour le Client
     */
    public function createPaymentLink($montant, $description, $reservationId = null, $demandeVisiteId = null)
    {
        // Préparation du Payload générique pour Leekpay API
        // Ces variables sont adaptées au standard REST souvent attendu par Leekpay
        $payload = [
            'amount' => intval($montant),
            'currency' => 'XOF',
            'description' => $description,
            'return_url' => route('paiement.leekpay.success', [
                'reservation_id' => $reservationId, 
                'demande_visite_id' => $demandeVisiteId
            ]),
            'cancel_url' => route('paiement.leekpay.cancel'),
            'webhook_url' => route('paiement.leekpay.webhook'),
            'metadata' => [
                'reservation_id' => $reservationId,
                'demande_visite_id' => $demandeVisiteId
            ]
        ];

        try {
            $endpoint = $this->apiUrl . '/payments'; // Revert to /payments as /transactions returns 404 on production
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->privateKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->successful() && (isset($response['url']) || isset($response['payment_url']))) {
                // Retourne l'URL de paiement générée par Leekpay
                return [
                    'success' => true,
                    'url' => $response['url'] ?? $response['payment_url'],   // Lien de redirection vers Leekpay Checkout
                    'payment_id' => $response['id'] ?? $response['transaction_id'] ?? null, 
                ];
            }

            Log::error('Erreur Leekpay API', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            
            return [
                'success' => false,
                'error' => 'Erreur Leekpay (' . $response->status() . ') : ' . ($response->json()['message'] ?? 'Impossible de générer le lien de paiement.')
            ];


        } catch (\Exception $e) {
            Log::error('Exception Leekpay', ['msg' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Erreur de connexion globale au service de paiement Leekpay.'
            ];
        }
    }

    /**
     * Valide le Webhook reçu en provenance de Leekpay
     */
    public function handleWebhook($request)
    {
        // Extraction du payload
        $payload = $request->all();
        $signature = $request->header('X-Leekpay-Signature'); // Header hypothétique de sécurité

        // TODO: Vérifier la signature si LeekPay le demande dans sa doc
        
        $status = $payload['status'] ?? null;
        $metadata = $payload['metadata'] ?? [];

        if ($status === 'SUCCESS' || $status === 'COMPLETED') {
            return DB::transaction(function () use ($payload, $metadata) {
                // Éviter les paiements en double
                $transactionId = $payload['transaction_id'] ?? ($payload['id'] ?? uniqid());
                
                if (Paiement::query()->where('reference_transaction', $transactionId)->exists()) {
                    return true;
                }

                $paiement = new Paiement([
                    'montant' => $payload['amount'] ?? 0,
                    'date_paiement' => now(),
                    'methode_paiement' => $payload['payment_method'] ?? 'leekpay_mobile', // Mobile money ou Carte
                    'reference_transaction' => $transactionId,
                    'statut' => 'valide',
                    'notes' => 'Paiement via Leekpay - Réf Tx: ' . $transactionId,
                ]);

                // On associe soit à une réservation, soit à une demande de visite !
                if (!empty($metadata['reservation_id'])) {
                    $paiement->id_reservation = $metadata['reservation_id'];
                    $paiement->save();
                    
                    // Valider la réservation
                    $reservation = Reservation::query()->find($metadata['reservation_id']);
                    if ($reservation) {
                        $reservation->update([
                            'statut' => 'terminee',
                            'acompte_paye' => $paiement->montant
                        ]);
                    }
                } 
                elseif (!empty($metadata['demande_visite_id'])) {
                    $paiement->id_demande_visite = $metadata['demande_visite_id']; // Si on gère ce genre de paiement
                    $paiement->save();
                    
                    // Passer la demande de visite à Payer / Confirmée
                    $demandeVisite = DemandeVisite::query()->find($metadata['demande_visite_id']);
                    if ($demandeVisite) {
                        $demandeVisite->update(['statut' => 'payee_confirmable']);
                    }
                }

                return true;
            });
        }

        return false;
    }
}
