<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use Illuminate\Http\Request;

class EquipementController extends Controller
{
    public function index()
    {
        $equipements = Equipement::paginate(10); // Show 10 items per page
        return view('admin.equipements.index', compact('equipements'));
    }

    public function create()
    {
        return view('admin.equipements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:50'
        ]);

        Equipement::create($validated);

        return redirect()->route('admin.equipements.index')
            ->with('success', 'Équipement créé avec succès.');
    }

    public function show(Equipement $equipement)
    {
        return view('admin.equipements.show', compact('equipement'));
    }

    public function edit(Equipement $equipement)
    {
        return view('admin.equipements.edit', compact('equipement'));
    }

    public function update(Request $request, Equipement $equipement)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:50'
        ]);

        $equipement->update($validated);

        return redirect()->route('admin.equipements.index')
            ->with('success', 'Équipement mis à jour avec succès.');
    }

    public function destroy(Equipement $equipement)
    {
        try {
            $equipement->delete();
            return redirect()->route('admin.equipements.index')
                ->with('success', 'Équipement supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cet équipement car il est utilisé par des appartement.');
        }
    }
}
