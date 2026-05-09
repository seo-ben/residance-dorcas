<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Propriete;
use App\Models\Chambre;
use Illuminate\Http\Request;

class ProprieteController extends Controller
{
    /**
     * @group Client - Propriétés
     * Liste des propriétés actives
     */
    public function index()
    {
        $proprietes = Propriete::with(['medias'])
            ->where('statut', 'actif')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $proprietes
        ]);
    }

    /**
     * @group Client - Propriétés
     * Détails d'une propriété et ses appartements disponibles
     */
    public function show($id)
    {
        $propriete = Propriete::with(['medias'])->findOrFail($id);
        
        $appartement = Chambre::with(['typeChambre', 'medias', 'equipements', 'avis'])
            ->where('id_propriete', $id)
            ->where('statut', 'disponible')
            ->get();
        
        // Calcul de la note moyenne pour chaque chambre
        $appartement->transform(function ($chambre) {
            $chambre->note_moyenne = $chambre->avis->avg('note') ?? 0;
            return $chambre;
        });
        
        return response()->json([
            'success' => true,
            'data' => [
                'propriete' => $propriete,
                'appartement' => $appartement
            ]
        ]);
    }
}
