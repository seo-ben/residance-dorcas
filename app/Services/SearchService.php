<?php

namespace App\Services;

use App\Models\Chambre;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    /**
     * Recherche avancée type "Booking.com"
     */
    public function search(array $filters)
    {
        $query = Chambre::with(['typeChambre', 'propriete', 'medias', 'equipements', 'avis']);

        // 1. Logique de Disponibilité (Cœur de Booking.com)
        if (!empty($filters['date_arrivee']) && !empty($filters['date_depart'])) {
            $arrivee = Carbon::parse($filters['date_arrivee']);
            $depart = Carbon::parse($filters['date_depart']);

            $query->whereDoesntHave('detailsReservations.reservation', function (Builder $q) use ($arrivee, $depart) {
                $q->where('statut', '!=', 'annulee')
                  ->where(function ($query) use ($arrivee, $depart) {
                      $query->whereBetween('date_arrivee', [$arrivee, $depart->copy()->subDay()])
                            ->orWhereBetween('date_depart', [$arrivee->copy()->addDay(), $depart])
                            ->orWhere(function ($query) use ($arrivee, $depart) {
                                $query->where('date_arrivee', '<=', $arrivee)
                                      ->where('date_depart', '>=', $depart);
                            });
                  });
            });

            // Exclure aussi les périodes d'indisponibilité manuelle (maintenance, etc.)
            $query->whereDoesntHave('periodesIndisponibilite', function (Builder $q) use ($arrivee, $depart) {
                $q->where(function ($query) use ($arrivee, $depart) {
                    $query->whereBetween('date_debut', [$arrivee, $depart])
                          ->orWhereBetween('date_fin', [$arrivee, $depart]);
                });
            });
        }

        // 2. Filtres Basiques
        if (!empty($filters['id_type_chambre'])) {
            $query->where('id_type_chambre', $filters['id_type_chambre']);
        }

        if (!empty($filters['id_propriete'])) {
            $query->where('id_propriete', $filters['id_propriete']);
        }

        // 3. Capacité (Logique Booking: au moins X personnes)
        if (!empty($filters['capacite'])) {
            // Ici on pourrait joindre typeChambre si la capacité est définie sur le type
            $query->whereHas('typeChambre', function($q) use ($filters) {
                $q->where('capacite_max', '>=', $filters['capacite']);
            });
        }

        // 4. Budget (Prix par nuit)
        if (!empty($filters['prix_min'])) {
            $query->where('prix_base', '>=', $filters['prix_min']);
        }
        if (!empty($filters['prix_max'])) {
            $query->where('prix_base', '<=', $filters['prix_max']);
        }

        // 5. Équipements (Logique AND: doit avoir TOUS les équipements sélectionnés)
        if (!empty($filters['equipements']) && is_array($filters['equipements'])) {
            foreach ($filters['equipements'] as $id_equipement) {
                $query->whereHas('equipements', function($q) use ($id_equipement) {
                    $q->where('equipements.id', $id_equipement);
                });
            }
        }

        // 6. Tri (Par défaut: Popularité/Recommandation)
        $tri = $filters['sort'] ?? 'recommande';
        switch ($tri) {
            case 'prix_asc': $query->orderBy('prix_base', 'asc'); break;
            case 'prix_desc': $query->orderBy('prix_base', 'desc'); break;
            case 'top_rated': 
                // Logique simplifiée: on pourrait faire un join pour trier par moyenne d'avis
                break;
            default: $query->latest(); break;
        }

        return $query->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Logique de tarification dynamique (Yield Management)
     * Booking.com ajuste les prix selon la demande.
     */
    public function getDynamicPrice(Chambre $chambre, Carbon $date)
    {
        // ... (existing logic)
    }

    /**
     * Recherche globale instantanée pour le mobile
     */
    public function globalSearch($term)
    {
        if (empty($term)) return [];

        $results = [];

        // 1. Rechercher dans les Appartements/Chambres
        $chambres = Chambre::with(['typeChambre', 'propriete'])
            ->where('nom', 'LIKE', "%$term%")
            ->orWhereHas('typeChambre', function($q) use ($term) {
                $q->where('nom', 'LIKE', "%$term%");
            })
            ->orWhereHas('propriete', function($q) use ($term) {
                $q->where('nom', 'LIKE', "%$term%")
                  ->orWhere('adresse', 'LIKE', "%$term%");
            })
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'appartement',
                    'id' => $item->id,
                    'title' => $item->nom,
                    'subtitle' => $item->propriete->nom . ' - ' . $item->typeChambre->nom,
                    'image' => $item->image_url, // Assurez-vous que cet attribut existe
                ];
            });
        
        $results = array_merge($results, $chambres->toArray());

        // 2. Rechercher dans les Services
        $services = \App\Models\Service::where('nom', 'LIKE', "%$term%")
            ->orWhere('description', 'LIKE', "%$term%")
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'service',
                    'id' => $item->id,
                    'title' => $item->nom,
                    'subtitle' => number_format($item->prix, 0, ',', ' ') . ' FCFA',
                    'image' => $item->image_url,
                ];
            });

        $results = array_merge($results, $services->toArray());

        // 3. Rechercher dans les Véhicules
        $vehicules = \App\Models\Vehicule::where('marque', 'LIKE', "%$term%")
            ->orWhere('modele', 'LIKE', "%$term%")
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'vehicule',
                    'id' => $item->id,
                    'title' => $item->marque . ' ' . $item->modele,
                    'subtitle' => number_format($item->prix_journalier, 0, ',', ' ') . ' FCFA / jour',
                    'image' => $item->image_url,
                ];
            });

        $results = array_merge($results, $vehicules->toArray());

        return $results;
    }
}        ->where('date_debut', '<=', $date)
            ->where('date_fin', '>=', $date)
            ->first();

        return $tarifSpecial ? $tarifSpecial->prix : $chambre->prix_base;
    }
}
