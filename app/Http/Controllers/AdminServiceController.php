<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\CommandeService;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
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
            'notes_admin' => 'nullable|string'
        ]);

        $order->update([
            'statut' => $request->statut,
            'notes_admin' => $request->notes_admin
        ]);

        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}
