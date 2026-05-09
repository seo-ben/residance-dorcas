<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    /**
     * @group Client - Favoris
     * Liste des favoris de l'utilisateur
     */
    public function index()
    {
        $favoris = Auth::user()->favoris()->with(['typeChambre', 'propriete', 'medias'])->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $favoris
        ]);
    }

    /**
     * @group Client - Favoris
     * Ajouter/Retirer un appartement des favoris
     */
    public function toggle(Request $request, Chambre $chambre)
    {
        $user = Auth::user();
        if ($user->favoris()->where('chambre_id', $chambre->id)->exists()) {
            $user->favoris()->detach($chambre->id);
            return response()->json([
                'success' => true,
                'status' => 'removed',
                'message' => 'Appartement retiré des favoris'
            ]);
        } else {
            $user->favoris()->attach($chambre->id);
            return response()->json([
                'success' => true,
                'status' => 'added',
                'message' => 'Appartement ajouté aux favoris'
            ]);
        }
    }
}
