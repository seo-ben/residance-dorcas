<?php

namespace App\Http\Controllers;

use App\Models\TypeChambre;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class TypeChambreController extends Controller
{
    public function index()
    {
        $typeschambres = TypeChambre::with(['medias' => function ($query) {
            $query->where('est_couverture', true)->orWhere('ordre', 0);
        }])->latest()->paginate(9);
        return view('admin.types-chambres.index', compact('typeschambres'));
    }

    public function create()
    {
        return view('admin.types-chambres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'capacite_standard' => 'required|integer|min:1',
            'capacite_max' => 'required|integer|min:1',
            'superficie' => 'required|numeric|min:0',
            'etage_type' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_principale' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $typeChambre = TypeChambre::create($validated);

        // Gestion de l'image principale
        if ($request->hasFile('image_principale')) {
            $this->handleMainImage($request->file('image_principale'), $typeChambre);
        }

        // Gestion des images supplémentaires
        if ($request->hasFile('images')) {
            $this->handleAdditionalImages($request->file('images'), $typeChambre);
        }

        return redirect()->route('admin.types-appartement.index')
            ->with('success', 'Type de chambre créé avec succès.');
    }

    public function show(TypeChambre $typeChambre)
    {
        $typeChambre->load(['medias', 'appartement']);
        return view('admin.types-appartement.show', compact('typeChambre'));
    }

    public function edit(TypeChambre $typeChambre)
    {
        $typeChambre->load('medias');
        return view('admin.types-appartement.edit', compact('typeChambre'));
    }

    public function update(Request $request, TypeChambre $typeChambre)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'capacite_standard' => 'required|integer|min:1',
            'capacite_max' => 'required|integer|min:1',
            'superficie' => 'required|numeric|min:0',
            'etage_type' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $typeChambre->update($validated);

        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image principale
            $oldMainImage = Media::where('id_reference', $typeChambre->id)
                ->where('type_reference', 'type_chambre')
                ->where('est_couverture', true)
                ->first();

            if ($oldMainImage) {
                Storage::delete($oldMainImage->chemin_fichier);
                $oldMainImage->delete();
            }

            $this->handleMainImage($request->file('image_principale'), $typeChambre);
        }

        if ($request->hasFile('images')) {
            $this->handleAdditionalImages($request->file('images'), $typeChambre);
        }

        return redirect()->route('admin.types-appartement.index')
            ->with('success', 'Type de chambre mis à jour avec succès.');
    }

    public function destroy(TypeChambre $typeChambre)
    {
        // Supprimer les médias associés
        $medias = Media::where('id_reference', $typeChambre->id)
            ->where('type_reference', 'type_chambre')
            ->get();

        foreach ($medias as $media) {
            Storage::delete($media->chemin_fichier);
            $media->delete();
        }

        $typeChambre->delete();

        return redirect()->route('admin.types-appartement.index')
            ->with('success', 'Type de chambre supprimé avec succès.');
    }

    private function handleMainImage($image, TypeChambre $typeChambre)
    {
        $filename = Str::slug($typeChambre->nom) . '-main-' . time() . '.' . $image->getClientOriginalExtension();

        // Créer les différentes tailles d'image
        $this->createImageVersions($image, $filename);

        // Déterminer le type de média basé sur l'extension
        $extension = strtolower($image->getClientOriginalExtension());
        $typeMedia = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'photo' : 'document';

        Media::create([
            'id_reference' => $typeChambre->id,
            'type_reference' => 'type_chambre',
            'type_media' => $typeMedia,
            'titre' => $typeChambre->nom . ' - Image principale',
            'chemin_fichier' => 'types-appartement/original/' . $filename,
            'est_couverture' => true,
            'ordre' => 0,
            'date_ajout' => now()
        ]);
    }

    private function handleAdditionalImages($images, TypeChambre $typeChambre)
    {
        $ordre = Media::where('id_reference', $typeChambre->id)
            ->where('type_reference', 'type_chambre')
            ->count();

        foreach ($images as $image) {
            $filename = Str::slug($typeChambre->nom) . '-' . time() . '-' . $ordre . '.' . $image->getClientOriginalExtension();

            // Créer les différentes tailles d'image
            $this->createImageVersions($image, $filename);

            // Déterminer le type de média basé sur l'extension
            $extension = strtolower($image->getClientOriginalExtension());
            $typeMedia = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'photo' : 'document';

            Media::create([
                'id_reference' => $typeChambre->id,
                'type_reference' => 'type_chambre',
                'type_media' => $typeMedia,
                'titre' => $typeChambre->nom . ' - Image ' . ($ordre + 1),
                'chemin_fichier' => 'types-appartement/original/' . $filename,
                'est_couverture' => false,
                'ordre' => $ordre + 1,
                'date_ajout' => now()
            ]);

            $ordre++;
        }
    }

    private function createImageVersions($image, $filename)
    {
        $versions = [
            'thumb' => [150, 150],
            'medium' => [400, 300],
            'large' => [800, 600]
        ];

        try {
            // Sauvegarder l'image originale d'abord
            $originalPath = $image->storeAs('public/types-appartement/original', $filename);
            $originalFullPath = storage_path('app/' . $originalPath);

            // Vérifier si le fichier a été sauvegardé correctement
            if (!file_exists($originalFullPath)) {
                throw new \Exception("Erreur: Le fichier original n'a pas pu être sauvegardé.");
            }

            // Vérifier la taille du fichier
            if (filesize($originalFullPath) == 0) {
                throw new \Exception("Erreur: Le fichier sauvegardé est vide.");
            }

            // Obtenir le type MIME de l'image
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            // Vérifier le type d'image avec getimagesize
            $imageInfo = getimagesize($originalFullPath);
            if ($imageInfo === false) {
                throw new \Exception("Erreur: Le fichier '{$filename}' n'est pas une image valide.");
            }

            // Vérifier la correspondance entre l'extension et le type réel
            $realImageType = $imageInfo[2];
            $expectedTypes = [
                'jpg' => [IMAGETYPE_JPEG],
                'jpeg' => [IMAGETYPE_JPEG],
                'png' => [IMAGETYPE_PNG]
            ];

            $extensionLower = strtolower($extension);
            if (
                !isset($expectedTypes[$extensionLower]) ||
                !in_array($realImageType, $expectedTypes[$extensionLower])
            ) {
                throw new \Exception("Erreur: L'extension '{$extension}' ne correspond pas au type réel de l'image.");
            }

            // Créer l'image source avec gestion d'erreur
            $source = null;
            if ($extensionLower === 'jpg' || $extensionLower === 'jpeg') {
                $source = @imagecreatefromjpeg($originalFullPath);
            } elseif ($extensionLower === 'png') {
                $source = @imagecreatefrompng($originalFullPath);
            } else {
                throw new \Exception("Format d'image non pris en charge: " . $extension);
            }

            // Vérifier si la création de l'image source a réussi
            if ($source === false || $source === null) {
                throw new \Exception("Erreur: Impossible de lire le fichier image '{$filename}'. Le fichier pourrait être corrompu.");
            }

            // Obtenir les dimensions de l'image source
            $sourceWidth = imagesx($source);
            $sourceHeight = imagesy($source);

            if ($sourceWidth === false || $sourceHeight === false) {
                imagedestroy($source);
                throw new \Exception("Erreur: Impossible d'obtenir les dimensions de l'image.");
            }

            foreach ($versions as $version => $dimensions) {
                try {
                    // Créer le répertoire si nécessaire
                    $path = storage_path('app/public/types-appartement/' . $version);
                    if (!file_exists($path)) {
                        if (!mkdir($path, 0755, true)) {
                            throw new \Exception("Erreur: Impossible de créer le répertoire '{$path}'.");
                        }
                    }

                    $targetWidth = $dimensions[0];
                    $targetHeight = $dimensions[1];

                    // Créer une image cible
                    $target = imagecreatetruecolor($targetWidth, $targetHeight);
                    if ($target === false) {
                        throw new \Exception("Erreur: Impossible de créer l'image cible pour la version '{$version}'.");
                    }

                    // Préserver la transparence pour PNG
                    if ($extensionLower === 'png') {
                        imagealphablending($target, false);
                        imagesavealpha($target, true);
                        $transparent = imagecolorallocatealpha($target, 255, 255, 255, 127);
                        imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $transparent);
                    } else {
                        // Fond blanc pour les autres formats
                        $white = imagecolorallocate($target, 255, 255, 255);
                        imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $white);
                    }

                    // Calculer les dimensions pour ajustement (fit)
                    $sourceRatio = $sourceWidth / $sourceHeight;
                    $targetRatio = $targetWidth / $targetHeight;

                    if ($sourceRatio > $targetRatio) {
                        // L'image source est plus large
                        $newWidth = $sourceHeight * $targetRatio;
                        $srcX = ($sourceWidth - $newWidth) / 2;
                        $srcY = 0;
                        $srcWidth = $newWidth;
                        $srcHeight = $sourceHeight;
                    } else {
                        // L'image source est plus haute
                        $newHeight = $sourceWidth / $targetRatio;
                        $srcX = 0;
                        $srcY = ($sourceHeight - $newHeight) / 2;
                        $srcWidth = $sourceWidth;
                        $srcHeight = $newHeight;
                    }

                    // Redimensionner et recadrer l'image
                    $resampleResult = imagecopyresampled(
                        $target,
                        $source,
                        0,
                        0,                    // Position dans l'image cible
                        $srcX,
                        $srcY,            // Position dans l'image source
                        $targetWidth,
                        $targetHeight, // Dimensions de la cible
                        $srcWidth,
                        $srcHeight    // Dimensions de la partie source à utiliser
                    );

                    if (!$resampleResult) {
                        imagedestroy($target);
                        throw new \Exception("Erreur: Échec du redimensionnement pour la version '{$version}'.");
                    }

                    // Sauvegarder l'image dans le bon format
                    $fullPath = $path . '/' . $filename;
                    $saveResult = false;

                    if ($extensionLower === 'jpg' || $extensionLower === 'jpeg') {
                        $saveResult = imagejpeg($target, $fullPath, 90);
                    } elseif ($extensionLower === 'png') {
                        $saveResult = imagepng($target, $fullPath, 9);
                    }

                    if (!$saveResult) {
                        imagedestroy($target);
                        throw new \Exception("Erreur: Impossible de sauvegarder la version '{$version}' du fichier.");
                    }

                    // Libérer la mémoire
                    imagedestroy($target);
                } catch (\Exception $e) {
                    // Log l'erreur spécifique à cette version
                    \Log::error("Erreur lors de la création de la version '{$version}': " . $e->getMessage());

                    // Continuer avec les autres versions
                    continue;
                }
            }

            // Libérer la mémoire de l'image source
            imagedestroy($source);

            return true;
        } catch (\Exception $e) {
            // Log l'erreur générale
            \Log::error("Erreur dans createImageVersions pour '{$filename}': " . $e->getMessage());

            // Nettoyer les ressources si nécessaire
            if (isset($source) && is_resource($source)) {
                imagedestroy($source);
            }

            return view('admin.types-appartement.index');
        }
    }

    public function deleteImage(TypeChambre $typeChambre, Media $media)
    {
        // Verify that the media belongs to this type_chambre
        if ($media->id_reference !== $typeChambre->id || $media->type_reference !== 'type_chambre') {
            return redirect()->back()->with('error', 'Image non trouvée');
        }

        // Check if it's not the cover image
        if ($media->est_couverture) {
            return redirect()->back()->with('error', 'Impossible de supprimer l\'image principale');
        }

        // Delete the image files from all versions
        $versions = ['original', 'thumb', 'medium', 'large'];
        foreach ($versions as $version) {
            Storage::delete('public/types-appartement/' . $version . '/' . basename($media->chemin_fichier));
        }

        // Delete the media record
        $media->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès');
    }
    public function reorderImages(Request $request)
    {
        $mediaIds = $request->input('media_ids');
        foreach ($mediaIds as $order => $id) {
            Media::where('id', $id)->update(['ordre' => $order]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordre des images mis à jour'
        ]);
    }
}
