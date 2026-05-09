<?php
namespace App\Http\Controllers;

use App\Models\Chambre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Assurez-vous que l'utilisateur est connecté
    }

    public function toggle(Request $request, Chambre $chambre)
    {
        $user = Auth::user();
        if ($user->favoris()->where('chambre_id', $chambre->id)->exists()) {
            $user->favoris()->detach($chambre->id);
            return response()->json(['status' => 'removed', 'message' => 'Chambre retirée des favoris']);
        } else {
            $user->favoris()->attach($chambre->id);
            return response()->json(['status' => 'added', 'message' => 'Chambre ajoutée aux favoris']);
        }
    }

    public function index()
    {
        $favoris = Auth::user()->favoris()->paginate(10);
        return view('favoris.index', compact('favoris'));
    }
}
