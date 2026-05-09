<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\TypeChambre;
use App\Models\Propriete;
use App\Models\Equipement;
use App\Models\Reservation;
use App\Models\DetailReservation;
use App\Models\PeriodeIndisponibilite;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientChambreController extends Controller
{
    public function index(Request $request)
    {
        $query = Chambre::with(['typeChambre', 'propriete', 'medias', 'equipements', 'avis'])
            ->where('statut', 'disponible');

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

        // Filtre par dates de disponibilité
        if ($request->filled('date_arrivee') && $request->filled('date_depart')) {
            $dateArrivee = Carbon::parse($request->date_arrivee);
            $dateDepart = Carbon::parse($request->date_depart);

            // Exclure les appartement déjà réservées pour ces dates
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

            // Exclure les appartement en période d'indisponibilité
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

        // Filtre par équipements (conservé de l'ancienne version)
        if ($request->has('equipements') && is_array($request->equipements) && count($request->equipements) > 0) {
            $query->whereHas('equipements', function ($q) use ($request) {
                $q->whereIn('equipements.id', $request->equipements);
            }, '=', count($request->equipements));
        }

        // Récupération des résultats
        $appartement = $query->paginate(12);

        // Calcul de la note moyenne pour chaque chambre
        foreach ($appartement as $chambre) {
            $chambre->note_moyenne = $chambre->avis->avg('note') ?? 0;
        }

        // Options de filtres
        $typesappartement = TypeChambre::all();
        $proprietes = Propriete::all();
        $equipements = Equipement::all();

        return view('chambres.index', compact(
            'appartement',
            'typesappartement',
            'proprietes',
            'equipements'
        ));
    }
}
