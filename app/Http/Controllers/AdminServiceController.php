<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\CommandeService;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Support\Str;


class AdminServiceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Liste des services.
     */
    public function index()
    {
        $services = Service::orderBy('nom')->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Enregistrement d'un nouveau service.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree_estimee' => 'nullable|integer',
            'disponibilite' => 'required|in:24h,jour,horaires_specifiques',
            'horaires_debut' => 'nullable|required_if:disponibilite,horaires_specifiques',
            'horaires_fin' => 'nullable|required_if:disponibilite,horaires_specifiques',
            'statut' => 'required|in:actif,inactif'
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service créé avec succès.');
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Mise à jour d'un service.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree_estimee' => 'nullable|integer',
            'disponibilite' => 'required|in:24h,jour,horaires_specifiques',
            'horaires_debut' => 'nullable|required_if:disponibilite,horaires_specifiques',
            'horaires_fin' => 'nullable|required_if:disponibilite,horaires_specifiques',
            'statut' => 'required|in:actif,inactif'
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service mis à jour.');
    }

    /**
     * Suppression d'un service.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service supprimé.');
    }

    /**
     * Liste des commandes de services.
     */
    public function orders()
    {
        $orders = CommandeService::with(['client.user', 'reservation', 'details.service'])
            ->orderBy('date_commande', 'desc')
            ->paginate(15);

        return view('admin.services.orders', compact('orders'));
    }

    /**
     * Mise à jour du statut d'une commande.
     */
    public function updateOrderStatus(Request $request, CommandeService $order)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_cours,terminee,annulee',
            'statut_paiement' => 'required|in:non_paye,paye,rembourse',
            'methode_paiement' => 'nullable|string',
            'notes_admin' => 'nullable|string'
        ]);

        $oldStatutPaiement = $order->statut_paiement;

        $order->update([
            'statut' => $request->statut,
            'statut_paiement' => $request->statut_paiement,
            'notes_admin' => $request->notes_admin
        ]);

        // Créer un enregistrement de paiement si c'est marqué comme payé et que ça ne l'était pas
        if ($request->statut_paiement === 'paye' && $oldStatutPaiement !== 'paye') {
            \App\Models\Paiement::create([
                'id_commande_service' => $order->id,
                'id_reservation' => $order->id_reservation,
                'montant' => $order->prix_total,
                'date_paiement' => now(),
                'methode_paiement' => $request->methode_paiement ?? 'especes',
                'statut' => 'valide',
                'id_admin_validation' => auth()->id(),
                'notes' => "Encaissement manuel pour commande service #{$order->id}"
            ]);

            // Logger l'audit
            \App\Models\AuditLog::create([
                'id_utilisateur' => auth()->id(),
                'action' => 'encaissement_service',
                'description' => "Encaissement de {$order->prix_total} FCFA pour la commande #{$order->id}",
                'ip_address' => request()->ip()
            ]);
        }

        // Notification au client
        try {
            $message = "Le statut de votre commande de service #{$order->id} est désormais : " . ucfirst($request->statut) . ".";
            if ($request->notes_admin) {
                $message .= " Note de l'admin : " . $request->notes_admin;
            }

            $this->notificationService->sendNotification(
                $order->client->user,
                $order->reservation, // Peut être null si commande directe
                'service',
                'Mise à jour de votre commande de service',
                $message
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur notification service: " . $e->getMessage());
        }

        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}
