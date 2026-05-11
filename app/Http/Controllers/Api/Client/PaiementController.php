<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Reservation;
use App\Models\LocationVehicule;
use App\Models\CommandeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    /**
     * Déclarer un paiement effectué via Mobile Money ou Carte (Présentiel)
     */
    public function declarePayment(Request $request)
    {
        $request->validate([
            'type' => 'required|in:reservation,location_vehicule,commande_service',
            'item_id' => 'required',
            'montant' => 'required|numeric|min:1',
            'methode_paiement' => 'required|string|in:tmoney,flooz,carte_bancaire,virement,autre',
            'reference_transaction' => 'nullable|string|max:100',
            'preuve' => 'nullable|image|max:5120', // Max 5MB
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $preuvePath = null;
            if ($request->hasFile('preuve')) {
                $preuvePath = $request->file('preuve')->store('paiements/preuves', 'public');
            }

            $paiementData = [
                'montant' => $request->montant,
                'date_paiement' => now(),
                'methode_paiement' => $request->methode_paiement,
                'reference_transaction' => $request->reference_transaction,
                'statut' => 'en_attente', // En attente de validation par l'admin
                'preuve_paiement' => $preuvePath,
                'notes' => $request->notes ?? "Déclaration de paiement mobile",
            ];

            // Lier au bon modèle
            if ($request->type === 'reservation') {
                $item = Reservation::findOrFail($request->item_id);
                $paiementData['id_reservation'] = $item->id;
            } elseif ($request->type === 'location_vehicule') {
                $item = LocationVehicule::findOrFail($request->item_id);
                $paiementData['id_location_vehicule'] = $item->id;
                $paiementData['id_reservation'] = $item->id_reservation;
            } elseif ($request->type === 'commande_service') {
                $item = CommandeService::findOrFail($request->item_id);
                $paiementData['id_commande_service'] = $item->id;
                $paiementData['id_reservation'] = $item->id_reservation;
            }

            $paiement = Paiement::create($paiementData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Votre déclaration de paiement a été envoyée. Elle sera validée par notre équipe sous peu.',
                'data' => $paiement
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déclaration du paiement: ' . $e->getMessage()
            ], 500);
        }
    }
}
