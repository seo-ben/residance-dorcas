<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\TypeChambre;
use App\Models\Propriete;
use App\Models\Equipement;
use App\Models\Media;
use App\Models\ChambreEquipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ChambreController extends Controller
{
    public function index()
    {
        try {
            // Get all rooms with relationships
            $appartement = Chambre::with([
                'typeChambre',
                'propriete',
                'medias' => function ($query) {
                    $query->where('est_couverture', true)->orWhere('ordre', 0);
                },
                'equipements'
            ])->latest()->paginate(10);

            $typesappartement = TypeChambre::all();

            // Calculate statistics
            $stats = [
                'disponibles' => [
                    'count' => Chambre::where('statut', 'disponible')->count(),
                    'percentage' => $this->calculatePercentage('disponible')
                ],
                'occupees' => [
                    'count' => Chambre::where('statut', 'occupee')->count(),
                    'percentage' => $this->calculatePercentage('occupee')
                ],
                'maintenance' => [
                    'count' => Chambre::where('statut', 'maintenance')->count(),
                    'percentage' => $this->calculatePercentage('maintenance')
                ],
                'taux_occupation' => $this->calculateOccupancyRate()
            ];

            return view('admin.chambres.index', compact('appartement', 'stats', 'typesappartement'));
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'affichage des appartement: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des appartement.');
        }
    }

    /**
     * Calculate percentage for a specific status
     */
    private function calculatePercentage($status)
    {
        $totalRooms = Chambre::count();
        if ($totalRooms === 0) return 0;

        $statusCount = Chambre::where('statut', $status)->count();
        return round(($statusCount / $totalRooms) * 100);
    }

    /**
     * Calculate occupancy rate
     */
    private function calculateOccupancyRate()
    {
        $totalRooms = Chambre::count();
        if ($totalRooms === 0) return 0;

        $occupiedRooms = Chambre::where('statut', 'occupee')->count();
        return round(($occupiedRooms / $totalRooms) * 100);
    }

    public function create()
    {
        try {
            $typeappartement = TypeChambre::all();
            $proprietes = Propriete::all();
            $equipements = Equipement::all();

            return view('admin.chambres.create', compact('typeappartement', 'proprietes', 'equipements'));
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du formulaire.');
        }
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'id_propriete' => 'required|exists:proprietes,id',
            'id_type_chambre' => 'required|exists:types_chambres,id',
            'numero_chambre' => 'required|string|max:50|unique:chambres',
            'etage' => 'required|integer',
            'prix_base' => 'required|numeric|min:0',
            'statut' => 'required|in:disponible,occupee,maintenance,inactive',
            'notes' => 'nullable|string',
            'equipements' => 'array|nullable',
            'equipements.*' => 'exists:equipements,id',
            'quantites' => 'array|nullable',
            'quantites.*' => 'integer|min:1',
            'image_principale' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ], [
            'image_principale.required' => 'L\'image principale est obligatoire.',
            'image_principale.image' => 'Le fichier doit être une image.',
            'image_principale.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou webp.',
            'image_principale.max' => 'L\'image ne doit pas dépasser 5MB.',
            'images.*.image' => 'Tous les fichiers doivent être des images.',
            'images.*.mimes' => 'Les images doivent être au format jpeg, png, jpg, gif ou webp.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 5MB.',
        ]);

        try {
            DB::beginTransaction();

            // Créer la chambre
            $chambre = Chambre::create([
                'id_propriete' => $validated['id_propriete'],
                'id_type_chambre' => $validated['id_type_chambre'],
                'numero_chambre' => $validated['numero_chambre'],
                'etage' => $validated['etage'],
                'prix_base' => $validated['prix_base'],
                'statut' => $validated['statut'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Gérer les équipements
            if ($request->has('equipements') && is_array($request->equipements)) {
                foreach ($request->equipements as $equipementId) {
                    $quantite = $request->input('quantites.' . $equipementId, 1);
                    $chambre->equipements()->attach($equipementId, [
                        'quantite' => $quantite,
                        'notes' => null
                    ]);
                }
            }

            // Gestion de l'image principale
            if ($request->hasFile('image_principale')) {
                $imageFile = $request->file('image_principale');
                if ($imageFile->isValid()) {
                    $this->handleMainImage($imageFile, $chambre);
                } else {
                    throw new Exception('L\'image principale est invalide');
                }
            }

            // Gestion des images supplémentaires
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                if (is_array($images)) {
                    foreach ($images as $image) {
                        if ($image->isValid()) {
                            $this->handleAdditionalImages([$image], $chambre);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.chambres.index')
                ->with('success', 'Chambre créée avec succès.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la chambre: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la chambre: ' . $e->getMessage());
        }
    }

    public function show(Chambre $chambre)
    {
        try {
            $chambre->load(['typeChambre', 'propriete', 'medias', 'equipements']);
            return view('admin.chambres.show', compact('chambre'));
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'affichage de la chambre: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement de la chambre.');
        }
    }

    public function edit(Chambre $chambre)
    {
        try {
            $typeappartement = TypeChambre::all();
            $proprietes = Propriete::all();
            $equipements = Equipement::all();
            $chambre->load(['medias', 'equipements']);

            return view('admin.chambres.edit', compact('chambre', 'typeappartement', 'proprietes', 'equipements'));
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du chargement du formulaire.');
        }
    }

    public function update(Request $request, Chambre $chambre)
    {
        // Validation des données
        $validated = $request->validate([
            'id_propriete' => 'required|exists:proprietes,id',
            'id_type_chambre' => 'required|exists:types_chambres,id',
            'numero_chambre' => 'required|string|max:50|unique:chambres,numero_chambre,' . $chambre->id,
            'etage' => 'required|integer',
            'prix_base' => 'required|numeric|min:0',
            'statut' => 'required|in:disponible,occupee,maintenance,inactive',
            'notes' => 'nullable|string',
            'equipements' => 'array|nullable',
            'equipements.*' => 'exists:equipements,id',
            'quantites' => 'array|nullable',
            'quantites.*' => 'integer|min:1',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ], [
            'image_principale.image' => 'Le fichier doit être une image.',
            'image_principale.mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou webp.',
            'image_principale.max' => 'L\'image ne doit pas dépasser 5MB.',
            'images.*.image' => 'Tous les fichiers doivent être des images.',
            'images.*.mimes' => 'Les images doivent être au format jpeg, png, jpg, gif ou webp.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 5MB.',
        ]);

        try {
            DB::beginTransaction();

            // Mettre à jour les données de base
            $chambre->update([
                'id_propriete' => $validated['id_propriete'],
                'id_type_chambre' => $validated['id_type_chambre'],
                'numero_chambre' => $validated['numero_chambre'],
                'etage' => $validated['etage'],
                'prix_base' => $validated['prix_base'],
                'statut' => $validated['statut'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Mettre à jour les équipements
            $chambre->equipements()->detach(); // Supprimer toutes les relations existantes

            if ($request->has('equipements') && is_array($request->equipements)) {
                foreach ($request->equipements as $equipementId) {
                    $quantite = $request->input('quantites.' . $equipementId, 1);
                    $chambre->equipements()->attach($equipementId, [
                        'quantite' => $quantite,
                        'notes' => null
                    ]);
                }
            }

            // Gestion de la nouvelle image principale
            if ($request->hasFile('image_principale')) {
                $imageFile = $request->file('image_principale');
                if ($imageFile->isValid()) {
                    // Supprimer l'ancienne image principale
                    $oldMainImage = Media::where('id_reference', $chambre->id)
                        ->where('type_reference', 'chambre')
                        ->where('est_couverture', true)
                        ->first();

                    if ($oldMainImage) {
                        $this->deleteMediaFiles($oldMainImage);
                        $oldMainImage->delete();
                    }

                    // Ajouter la nouvelle image principale
                    $this->handleMainImage($imageFile, $chambre);
                }
            }

            // Gestion des nouvelles images supplémentaires
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                if (is_array($images)) {
                    foreach ($images as $image) {
                        if ($image->isValid()) {
                            $this->handleAdditionalImages([$image], $chambre);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.chambres.index')
                ->with('success', 'Chambre mise à jour avec succès.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour de la chambre: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la chambre: ' . $e->getMessage());
        }
    }

    public function destroy(Chambre $chambre)
    {
        try {
            DB::beginTransaction();

            // Supprimer les médias associés
            $medias = Media::where('id_reference', $chambre->id)
                ->where('type_reference', 'chambre')
                ->get();

            foreach ($medias as $media) {
                $this->deleteMediaFiles($media);
                $media->delete();
            }

            // Supprimer les relations avec les équipements
            $chambre->equipements()->detach();

            // Supprimer la chambre
            $chambre->delete();

            DB::commit();

            return redirect()->route('admin.chambres.index')
                ->with('success', 'Chambre supprimée avec succès.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression de la chambre: ' . $e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la suppression de la chambre: ' . $e->getMessage());
        }
    }

    /**
     * Gérer l'image principale
     */
    private function handleMainImage($image, Chambre $chambre)
    {
        try {
            // Générer un nom de fichier unique
            $filename = $this->generateUniqueFilename($chambre->numero_chambre, 'main', $image->getClientOriginalExtension());

            // Créer les différentes versions de l'image
            $this->createImageVersions($image, $filename);

            // Déterminer le type de média
            $typeMedia = $this->getMediaType($image->getClientOriginalExtension());

            // Créer l'enregistrement dans la base de données
            Media::create([
                'id_reference' => $chambre->id,
                'type_reference' => 'chambre',
                'type_media' => $typeMedia,
                'titre' => 'Chambre ' . $chambre->numero_chambre . ' - Image principale',
                'chemin_fichier' => 'appartement/original/' . $filename,
                'est_couverture' => true,
                'ordre' => 0,
                'date_ajout' => now()
            ]);

            Log::info('Image principale créée avec succès: ' . $filename);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de l\'image principale: ' . $e->getMessage());
            throw new Exception('Erreur lors de la création de l\'image principale: ' . $e->getMessage());
        }
    }

    /**
     * Gérer les images supplémentaires
     */
    private function handleAdditionalImages($images, Chambre $chambre)
    {
        try {
            // Obtenir le dernier ordre utilisé
            $lastOrder = Media::where('id_reference', $chambre->id)
                ->where('type_reference', 'chambre')
                ->max('ordre') ?? 0;

            foreach ($images as $image) {
                $lastOrder++;

                // Générer un nom de fichier unique
                $filename = $this->generateUniqueFilename($chambre->numero_chambre, $lastOrder, $image->getClientOriginalExtension());

                // Créer les différentes versions de l'image
                $this->createImageVersions($image, $filename);

                // Déterminer le type de média
                $typeMedia = $this->getMediaType($image->getClientOriginalExtension());

                // Créer l'enregistrement dans la base de données
                Media::create([
                    'id_reference' => $chambre->id,
                    'type_reference' => 'chambre',
                    'type_media' => $typeMedia,
                    'titre' => 'Chambre ' . $chambre->numero_chambre . ' - Image ' . $lastOrder,
                    'chemin_fichier' => 'appartement/original/' . $filename,
                    'est_couverture' => false,
                    'ordre' => $lastOrder,
                    'date_ajout' => now()
                ]);

                Log::info('Image supplémentaire créée avec succès: ' . $filename);
            }
        } catch (Exception $e) {
            Log::error('Erreur lors de la création des images supplémentaires: ' . $e->getMessage());
            throw new Exception('Erreur lors de la création des images supplémentaires: ' . $e->getMessage());
        }
    }

    /**
     * Générer un nom de fichier unique
     */
    private function generateUniqueFilename($numeroChambre, $suffix, $extension)
    {
        $baseName = Str::slug($numeroChambre) . '-' . $suffix . '-' . time() . '-' . uniqid();
        return $baseName . '.' . strtolower($extension);
    }

    /**
     * Déterminer le type de média basé sur l'extension
     */
    private function getMediaType($extension)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array(strtolower($extension), $imageExtensions) ? 'photo' : 'document';
    }

    /**
     * Créer les différentes versions d'une image
     */
    private function createImageVersions($image, $filename)
    {
        try {
            // Créer les répertoires nécessaires
            $directories = ['original', 'thumb', 'medium', 'large'];
            foreach ($directories as $dir) {
                $path = storage_path('app/public/appartement/' . $dir);
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
            }

            // Sauvegarder l'image originale
            $originalPath = $image->storeAs('public/appartement/original', $filename);

            if (!$originalPath) {
                throw new Exception('Impossible de sauvegarder l\'image originale');
            }

            $originalFullPath = storage_path('app/' . $originalPath);

            // Vérifier que le fichier existe
            if (!file_exists($originalFullPath)) {
                throw new Exception('Le fichier original n\'existe pas après la sauvegarde');
            }

            // Versions d'image à créer
            $versions = [
                'thumb' => [150, 150],
                'medium' => [400, 300],
                'large' => [800, 600]
            ];

            // Obtenir les informations de l'image
            $imageInfo = getimagesize($originalFullPath);
            if (!$imageInfo) {
                throw new Exception('Impossible de lire les informations de l\'image');
            }

            $sourceWidth = $imageInfo[0];
            $sourceHeight = $imageInfo[1];
            $mimeType = $imageInfo['mime'];

            // Créer l'image source selon le type
            switch ($mimeType) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($originalFullPath);
                    break;
                case 'image/png':
                    $source = imagecreatefrompng($originalFullPath);
                    break;
                case 'image/gif':
                    $source = imagecreatefromgif($originalFullPath);
                    break;
                case 'image/webp':
                    $source = imagecreatefromwebp($originalFullPath);
                    break;
                default:
                    throw new Exception('Format d\'image non pris en charge: ' . $mimeType);
            }

            if (!$source) {
                throw new Exception('Impossible de créer l\'image source');
            }

            // Créer les différentes versions
            foreach ($versions as $version => $dimensions) {
                $this->createResizedImage($source, $sourceWidth, $sourceHeight, $dimensions[0], $dimensions[1], $version, $filename, $mimeType);
            }

            // Libérer la mémoire
            imagedestroy($source);

            Log::info('Toutes les versions d\'image créées avec succès pour: ' . $filename);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création des versions d\'image: ' . $e->getMessage());
            throw new Exception('Erreur lors de la création des versions d\'image: ' . $e->getMessage());
        }
    }

    /**
     * Créer une image redimensionnée
     */
    private function createResizedImage($source, $sourceWidth, $sourceHeight, $targetWidth, $targetHeight, $version, $filename, $mimeType)
    {
        try {
            // Créer l'image cible
            $target = imagecreatetruecolor($targetWidth, $targetHeight);

            if (!$target) {
                throw new Exception('Impossible de créer l\'image cible');
            }

            // Préserver la transparence pour PNG et GIF
            if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                imagealphablending($target, false);
                imagesavealpha($target, true);
                $transparent = imagecolorallocatealpha($target, 255, 255, 255, 127);
                imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $transparent);
            } else {
                // Fond blanc pour les autres formats
                $white = imagecolorallocate($target, 255, 255, 255);
                imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $white);
            }

            // Calculer les dimensions pour un ajustement proportionnel
            $sourceRatio = $sourceWidth / $sourceHeight;
            $targetRatio = $targetWidth / $targetHeight;

            if ($sourceRatio > $targetRatio) {
                // Image plus large que haute
                $newWidth = $sourceHeight * $targetRatio;
                $srcX = ($sourceWidth - $newWidth) / 2;
                $srcY = 0;
                $srcWidth = $newWidth;
                $srcHeight = $sourceHeight;
            } else {
                // Image plus haute que large
                $newHeight = $sourceWidth / $targetRatio;
                $srcX = 0;
                $srcY = ($sourceHeight - $newHeight) / 2;
                $srcWidth = $sourceWidth;
                $srcHeight = $newHeight;
            }

            // Redimensionner l'image
            $result = imagecopyresampled(
                $target,
                $source,
                0,
                0,
                $srcX,
                $srcY,
                $targetWidth,
                $targetHeight,
                $srcWidth,
                $srcHeight
            );

            if (!$result) {
                throw new Exception('Erreur lors du redimensionnement de l\'image');
            }

            // Sauvegarder l'image dans le bon format
            $fullPath = storage_path('app/public/appartement/' . $version . '/' . $filename);

            switch ($mimeType) {
                case 'image/jpeg':
                    $saved = imagejpeg($target, $fullPath, 90);
                    break;
                case 'image/png':
                    $saved = imagepng($target, $fullPath, 9);
                    break;
                case 'image/gif':
                    $saved = imagegif($target, $fullPath);
                    break;
                case 'image/webp':
                    $saved = imagewebp($target, $fullPath, 90);
                    break;
                default:
                    throw new Exception('Format non pris en charge pour la sauvegarde');
            }

            if (!$saved) {
                throw new Exception('Impossible de sauvegarder l\'image redimensionnée');
            }

            // Libérer la mémoire
            imagedestroy($target);
        } catch (Exception $e) {
            if (isset($target)) {
                imagedestroy($target);
            }
            throw $e;
        }
    }

    /**
     * Supprimer les fichiers média
     */
    private function deleteMediaFiles($media)
    {
        try {
            $filename = basename($media->chemin_fichier);
            $versions = ['original', 'thumb', 'medium', 'large'];

            foreach ($versions as $version) {
                $path = 'public/appartement/' . $version . '/' . $filename;
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }

            Log::info('Fichiers média supprimés avec succès: ' . $filename);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression des fichiers média: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un média spécifique
     */
    public function deleteMedia($id)
    {
        try {
            DB::beginTransaction();

            $media = Media::where('id', $id)
                ->where('type_reference', 'chambre')
                ->firstOrFail();

            // Supprimer les fichiers
            $this->deleteMediaFiles($media);

            // Supprimer l'enregistrement
            $media->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Image supprimée avec succès']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du média: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
}
