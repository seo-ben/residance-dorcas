<?php

namespace App\Http\Controllers;

use App\Models\Propriete;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProprieteController extends Controller
{
    public function index()
    {
        $proprietes = Propriete::with('medias')->orderBy('created_at', 'desc')->get();
        return view('admin.proprietes.index', compact('proprietes'));
    }

    public function create()
    {
        return view('admin.proprietes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'etoiles' => 'required|integer|between:1,5',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'code_postal' => 'nullable|string',
            'pays' => 'nullable|string',
            'telephone' => 'required|string',
            'email' => 'required|email',
            'statut' => 'required|in:actif,inactif',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image_principale' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $propriete = Propriete::create($validated);

            // Gestion de l'image principale
            if ($request->hasFile('image_principale')) {
                $this->handleMainImage($request->file('image_principale'), $propriete);
            }

            // Gestion des images supplémentaires
            if ($request->hasFile('images')) {
                $this->handleAdditionalImages($request->file('images'), $propriete);
            }

            DB::commit();
            return redirect()->route('admin.proprietes.index')
                ->with('success', 'Propriété créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la création de la propriété.');
        }
    }
    public function show(Propriete $propriete)
    {
        $propriete->load(['medias']);
        return view('admin.proprietes.show', compact('propriete'));
    }
    public function edit(Propriete $propriete)
    {
        return view('admin.proprietes.edit', compact('propriete'));
    }

    public function update(Request $request, Propriete $propriete)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'etoiles' => 'required|integer|between:1,5',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'code_postal' => 'nullable|string',
            'pays' => 'nullable|string',
            'telephone' => 'required|string',
            'email' => 'required|email',
            'statut' => 'required|in:actif,inactif',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $propriete->update($validated);

            // Gestion de l'image principale
            if ($request->hasFile('image_principale')) {
                // Supprimer l'ancienne image principale
                $oldMainImage = Media::where('id_reference', $propriete->id)
                    ->where('type_reference', 'propriete')
                    ->where('est_couverture', true)
                    ->first();

                if ($oldMainImage) {
                    Storage::delete($oldMainImage->chemin_fichier);
                    $oldMainImage->delete();
                }

                $this->handleMainImage($request->file('image_principale'), $propriete);
            }

            // Gestion des images supplémentaires
            if ($request->hasFile('images')) {
                $this->handleAdditionalImages($request->file('images'), $propriete);
            }

            DB::commit();
            return redirect()->route('admin.proprietes.index')
                ->with('success', 'Propriété mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour de la propriété.');
        }
    }

    public function destroy(Propriete $propriete)
    {
        try {
            DB::beginTransaction();

            // Supprimer toutes les images associées
            foreach ($propriete->medias as $media) {
                Storage::delete($media->chemin_fichier);
                $media->delete();
            }

            $propriete->delete();

            DB::commit();
            return redirect()->route('admin.proprietes.index')
                ->with('success', 'Propriété supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la propriété.');
        }
    }

    private function handleMainImage($image, Propriete $propriete)
    {
        $filename = Str::slug($propriete->nom) . '-main-' . time() . '.' . $image->getClientOriginalExtension();
        
        $path = $image->storeAs('proprietes', $filename, 'public');

        Media::create([
            'id_reference' => $propriete->id,
            'type_reference' => 'propriete',
            'type_media' => 'photo',
            'titre' => $propriete->nom . ' - Image principale',
            'chemin_fichier' => $path,
            'est_couverture' => true,
            'ordre' => 0,
            'date_ajout' => now()
        ]);
    }

    private function handleAdditionalImages($images, Propriete $propriete)
    {
        $ordre = Media::where('id_reference', $propriete->id)
            ->where('type_reference', 'propriete')
            ->count();

        foreach ($images as $image) {
            $filename = Str::slug($propriete->nom) . '-' . time() . '-' . $ordre . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('proprietes', $filename, 'public');

            Media::create([
                'id_reference' => $propriete->id,
                'type_reference' => 'propriete',
                'type_media' => 'photo',
                'titre' => $propriete->nom . ' - Image ' . ($ordre + 1),
                'chemin_fichier' => $path,
                'est_couverture' => false,
                'ordre' => $ordre + 1,
                'date_ajout' => now()
            ]);

            $ordre++;
        }
    }

    public function deleteImage(Propriete $propriete, Media $media)
    {
        try {
            if ($media->id_reference !== $propriete->id) {
                return back()->with('error', 'Image non trouvée.');
            }

            Storage::delete($media->chemin_fichier);
            $media->delete();

            return back()->with('success', 'Image supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'image.');
        }
    }
}