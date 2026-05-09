<?php

namespace App\Http\Controllers;

use App\Models\Propriete;
use App\Models\Chambre;

use Illuminate\Http\Request;

class ClientProprietesController extends Controller
{
    /**
     * Affiche la liste des propriétés
     */
    public function index()
    {
        $proprietes = Propriete::with(['medias'])
            ->where('statut', 'actif')
            ->get();
            
        return view('chambres.proprietes.index', compact('proprietes'));
    }

    /**
     * Affiche les détails d'une propriété et ses appartement
     */
    public function show($id)
    {
        $propriete = Propriete::with(['medias'])->findOrFail($id);
        
        $appartement = Chambre::with(['typeChambre', 'medias', 'equipements', 'avis'])
            ->where('id_propriete', $id)
            ->where('statut', 'disponible')
            ->get();
        
        // Calcul de la note moyenne pour chaque chambre
        foreach ($appartement as $chambre) {
            $chambre->note_moyenne = $chambre->avis->avg('note') ?? 0;
        }
        
        return view('chambres.proprietes.show', compact('propriete', 'appartement'));
    }
}