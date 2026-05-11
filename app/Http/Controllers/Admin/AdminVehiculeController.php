<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicule;
use App\Models\VehiculeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class AdminVehiculeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        $vehicules = Vehicule::with('primaryImage')->latest()->paginate(10);
        return view('admin.vehicules.index', compact('vehicules'));
    }

    public function show(Vehicule $vehicule)
    {
        $vehicule->load(['images', 'locations.client.user', 'locations.reservation']);
        return view('admin.vehicules.show', compact('vehicule'));
    }

    public function create()
    {
        return view('admin.vehicules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:100',
            'modele' => 'required|string|max:100',
            'immatriculation' => 'required|string|max:20|unique:vehicules',
            'type' => 'required|string',
            'transmission' => 'required|string',
            'carburant' => 'required|string',
            'nb_places' => 'required|integer|min:1',
            'prix_journalier' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => 'required|in:disponible,loue,maintenance,indisponible',
            'caracteristiques' => 'nullable|array',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $vehicule = Vehicule::create($validated);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('vehicules', 'public');
                    VehiculeImage::create([
                        'id_vehicule' => $vehicule->id,
                        'chemin_image' => $path,
                        'est_principale' => ($index === 0),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.vehicules.index')->with('success', 'Véhicule créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function edit(Vehicule $vehicule)
    {
        $vehicule->load('images');
        return view('admin.vehicules.edit', compact('vehicule'));
    }

    public function update(Request $request, Vehicule $vehicule)
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:100',
            'modele' => 'required|string|max:100',
            'immatriculation' => 'required|string|max:20|unique:vehicules,immatriculation,' . $vehicule->id,
            'type' => 'required|string',
            'transmission' => 'required|string',
            'carburant' => 'required|string',
            'nb_places' => 'required|integer|min:1',
            'prix_journalier' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'statut' => 'required|in:disponible,loue,maintenance,indisponible',
            'caracteristiques' => 'nullable|array',
        ]);

        $vehicule->update($validated);

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('vehicules', 'public');
                VehiculeImage::create([
                    'id_vehicule' => $vehicule->id,
                    'chemin_image' => $path,
                    'est_principale' => false,
                ]);
            }
        }

        return redirect()->route('admin.vehicules.index')->with('success', 'Véhicule mis à jour.');
    }

    public function destroy(Vehicule $vehicule)
    {
        foreach ($vehicule->images as $image) {
            Storage::disk('public')->delete($image->chemin_image);
        }
        $vehicule->delete();
        return redirect()->route('admin.vehicules.index')->with('success', 'Véhicule supprimé.');
    }

    public function setPrimaryImage(Vehicule $vehicule, VehiculeImage $image)
    {
        VehiculeImage::where('id_vehicule', $vehicule->id)->update(['est_principale' => false]);
        $image->update(['est_principale' => true]);
        return back()->with('success', 'Image principale mise à jour.');
    }

    public function deleteImage(VehiculeImage $image)
    {
        Storage::disk('public')->delete($image->chemin_image);
        $image->delete();
        return back()->with('success', 'Image supprimée.');
    }

    /**
     * Liste des locations de véhicules.
     */
    public function rentals()
    {
        $rentals = \App\Models\LocationVehicule::with(['vehicule', 'client.user', 'reservation'])
            ->latest()
            ->paginate(15);

        return view('admin.vehicules.rentals', compact('rentals'));
    }

    /**
     * Mise à jour du statut d'une location.
     */
    public function updateRentalStatus(Request $request, \App\Models\LocationVehicule $rental)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_cours,terminee,annulee',
            'statut_paiement' => 'required|in:non_paye,partiel,paye,rembourse',
            'methode_paiement' => 'nullable|string'
        ]);

        $oldStatutPaiement = $rental->statut_paiement;

        $rental->update([
            'statut' => $request->statut,
            'statut_paiement' => $request->statut_paiement,
            'notes' => $request->notes
        ]);

        // Créer un enregistrement de paiement si c'est marqué comme payé et que ça ne l'était pas
        if ($request->statut_paiement === 'paye' && $oldStatutPaiement !== 'paye') {
            \App\Models\Paiement::create([
                'id_location_vehicule' => $rental->id,
                'id_reservation' => $rental->id_reservation,
                'montant' => $rental->prix_total,
                'date_paiement' => now(),
                'methode_paiement' => $request->methode_paiement ?? 'especes',
                'statut' => 'valide',
                'id_admin_validation' => auth()->id(),
                'notes' => "Encaissement manuel pour location véhicule #{$rental->id}"
            ]);

            // Logger l'audit
            \App\Models\AuditLog::create([
                'id_utilisateur' => auth()->id(),
                'action' => 'encaissement_vehicule',
                'description' => "Encaissement de {$rental->prix_total} FCFA pour la location #{$rental->id}",
                'ip_address' => request()->ip()
            ]);
        }

        // Si la location est confirmée ou en cours, on peut mettre à jour le statut du véhicule
        if (in_array($request->statut, ['confirmee', 'en_cours'])) {
            $rental->vehicule->update(['statut' => 'loue']);
        } elseif (in_array($request->statut, ['terminee', 'annulee'])) {
            $rental->vehicule->update(['statut' => 'disponible']);
        }

        // Notification au client
        try {
            $message = "Le statut de votre location de véhicule ({$rental->vehicule->marque} {$rental->vehicule->modele}) est désormais : " . ucfirst($request->statut) . ".";
            if ($request->statut_paiement === 'paye') {
                $message .= " Votre paiement a bien été confirmé.";
            }

            $this->notificationService->sendNotification(
                $rental->client->user,
                $rental->reservation,
                'vehicule',
                'Mise à jour de votre location',
                $message
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur notification véhicule: " . $e->getMessage());
        }

        return back()->with('success', 'Statut de la location mis à jour.');
    }
}
