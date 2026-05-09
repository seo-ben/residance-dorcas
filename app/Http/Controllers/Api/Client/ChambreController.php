<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\TypeChambre;
use App\Models\Propriete;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChambreController extends Controller
{
    /**
     * @group Client - Appartements
     * Liste des appartements disponibles avec filtres
     */
    public function index(Request $request)
    {
        $query = Chambre::with(['typeChambre', 'propriete', 'medias', 'equipements', 'avis'])
            ->where('statut', 'disponible');

        // Recherche par mot-clé
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_chambre', 'like', "%$search%")
                  ->orWhereHas('typeChambre', function($sq) use ($search) {
                      $sq->where('nom_type', 'like', "%$search%");
                  })
                  ->orWhereHas('propriete', function($sq) use ($search) {
                      $sq->where('nom_propriete', 'like', "%$search%");
                  });
            });
        }

        // Filtre par propriété
        if ($request->filled('propriete')) {
            $query->where('id_propriete', $request->propriete);
        }

        // Filtre par type de chambre
        if ($request->filled('type')) {
            $query->where('id_type_chambre', $request->type);
        }

        // Filtre par capacité
        if ($request->filled('capacite')) {
            $query->whereHas('typeChambre', function ($q) use ($request) {
                $q->where('capacite_standard', '>=', $request->capacite)
                    ->orWhere('capacite_max', '>=', $request->capacite);
            });
        }

        // Filtre par prix maximum
        if ($request->filled('prix_max')) {
            $query->where('prix_base', '<=', $request->prix_max);
        }

        // Filtre par prix minimum
        if ($request->filled('prix_min')) {
            $query->where('prix_base', '>=', $request->prix_min);
        }

        // Filtre par dates de disponibilité
        if ($request->filled('date_arrivee') && $request->filled('date_depart')) {
            $dateArrivee = Carbon::parse($request->date_arrivee);
            $dateDepart = Carbon::parse($request->date_depart);

            // Exclure les appartements déjà réservés pour ces dates
            $query->whereDoesntHave('detailsReservations', function ($q) use ($dateArrivee, $dateDepart) {
                $q->whereHas('reservation', function ($subQ) use ($dateArrivee, $dateDepart) {
                    $subQ->where('statut', '!=', 'annulee')
                        ->where(function ($dateQ) use ($dateArrivee, $dateDepart) {
                            $dateQ->whereBetween('date_arrivee', [$dateArrivee, $dateDepart])
                                ->orWhereBetween('date_depart', [$dateArrivee, $dateDepart])
                                ->orWhere(function ($overlapQ) use ($dateArrivee, $dateDepart) {
                                    $overlapQ->where('date_arrivee', '<=', $dateArrivee)
                                        ->where('date_depart', '>=', $dateDepart);
                                });
                        });
                });
            });

            // Exclure les appartements en période d'indisponibilité
            $query->whereDoesntHave('periodesIndisponibilite', function ($q) use ($dateArrivee, $dateDepart) {
                $q->where(function ($dateQ) use ($dateArrivee, $dateDepart) {
                    $dateQ->whereBetween('date_debut', [$dateArrivee, $dateDepart])
                        ->orWhereBetween('date_fin', [$dateArrivee, $dateDepart])
                        ->orWhere(function ($overlapQ) use ($dateArrivee, $dateDepart) {
                            $overlapQ->where('date_debut', '<=', $dateArrivee)
                                ->where('date_fin', '>=', $dateDepart);
                        });
                });
            });
        }

        // Filtre par équipements
        if ($request->has('equipements') && is_array($request->equipements) && count($request->equipements) > 0) {
            $query->whereHas('equipements', function ($q) use ($request) {
                $q->whereIn('equipements.id', $request->equipements);
            }, '=', count($request->equipements));
        }

        $appartement = $query->paginate($request->input('per_page', 12));

        // Transformation pour l'API
        $appartement->getCollection()->transform(function ($chambre) {
            $chambre->note_moyenne = $chambre->avis->avg('note') ?? 0;
            return $chambre;
        });

        return response()->json([
            'success' => true,
            'data' => $appartement,
            'filters' => [
                'types' => TypeChambre::all(),
                'proprietes' => Propriete::all(),
                'equipements' => Equipement::all(),
            ]
        ]);
    }

    /**
     * @group Client - Appartements
     * Détails d'un appartement
     */
    public function show($id)
    {
        $chambre = Chambre::with(['typeChambre', 'propriete', 'medias', 'equipements', 'avis.client'])
            ->findOrFail($id);

        $chambre->note_moyenne = $chambre->avis->avg('note') ?? 0;

        return response()->json([
            'success' => true,
            'data' => $chambre
        ]);
    }
}
