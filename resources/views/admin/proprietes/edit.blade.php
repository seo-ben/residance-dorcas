@extends('layouts.playout')
@section('content')
<div class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête de la page -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                {{ __('Modifier la propriété') }}
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Modifiez les informations de votre propriété ci-dessous
            </p>
        </div>

        <!-- Carte principale du formulaire -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Barre de progression -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2"></div>
            
            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div class="p-4 bg-red-50 border-l-4 border-red-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Certains champs nécessitent votre attention
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire principal -->
            <form action="{{ route('admin.proprietes.update', $propriete) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                @method('PUT')

                <!-- Section 1: Informations générales -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-full mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </span>
                        Informations générales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la propriété </label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $propriete->nom) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="space-y-1">
                            <label for="etoiles" class="block text-sm font-medium text-gray-700">Classification </label>
                            <select name="etoiles" id="etoiles" required
                                class="mt-1 block w-full rounded-md p-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" class="p-2" {{ old('etoiles', $propriete->etoiles) == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 1 ? 'étoile' : 'étoiles' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Localisation -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-full mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        Localisation
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse complète </label>
                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $propriete->adresse) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700">Ville </label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville', $propriete->ville) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="code_postal" class="block text-sm font-medium text-gray-700">Code postal </label>
                            <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal', $propriete->code_postal) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="pays" class="block text-sm font-medium text-gray-700">Pays </label>
                            <input type="text" name="pays" id="pays" value="{{ old('pays', $propriete->pays) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $propriete->latitude) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex-1">
                                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $propriete->longitude) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Contact -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-full mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Informations de contact
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', $propriete->telephone) }}" required
                                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email', $propriete->email) }}" required
                                    class="pl-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Description et Statut -->
                <div class="border-b border-gray-200 pb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-full mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        Description et Statut
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $propriete->description) }}</textarea>
                        </div>

                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700">Statut </label>
                            <select name="statut" id="statut" required
                                class="mt-1 block p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="actif" {{ old('statut', $propriete->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut', $propriete->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Images -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <span class="bg-blue-100 text-blue-600 p-1.5 rounded-full mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Images
                    </h2>

                    <!-- Image principale -->
                    <div class="mb-8">
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Image principale</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Image actuelle -->
                            @if($mainImage = $propriete->medias->where('est_couverture', true)->first())
                                <div class="relative group">
                                    <img src="{{ Storage::url($mainImage->chemin_fichier) }}" 
                                         alt="Image principale" 
                                         class="w-full h-48 object-cover rounded-lg shadow-sm">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                        <button type="button" 
                                                onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) document.getElementById('delete-image-{{ $mainImage->id }}').submit();"
                                                class="hidden group-hover:block bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Upload nouvelle image principale -->
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Cliquez pour télécharger</span> ou glissez-déposez</p>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 2MB)</p>
                                    </div>
                                    <input type="file" name="image_principale" class="hidden" id="image_principale" accept="image/"/>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Images supplémentaires -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-4">Images supplémentaires</h3>
                        
                        <!-- Images actuelles -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            @foreach($propriete->medias->where('est_couverture', false) as $image)
                                <div class="relative group">
                                    <img src="{{ Storage::url($image->chemin_fichier) }}" 
                                         alt="Image supplémentaire" 
                                         class="w-full h-32 object-cover rounded-lg shadow-sm">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                        <button type="button" 
                                                onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) document.getElementById('delete-image-{{ $image->id }}').submit();"
                                                class="hidden group-hover:block bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition-colors">
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Upload nouvelles images -->
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Cliquez pour ajouter des images</span></p>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 2MB)</p>
                                </div>
                                <input type="file" name="images[]" class="hidden" id="images" accept="image/" multiple/>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-8">
                    <a href="{{ route('admin.proprietes.index') }}" 
                       class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Mettre à jour la propriété
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview for main image
    const mainImageInput = document.getElementById('image_principale');
    if (mainImageInput) {
        mainImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = mainImageInput.closest('div').querySelector('img');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        const newPreview = document.createElement('img');
                        newPreview.src = e.target.result;
                        newPreview.classList.add('w-full', 'h-48', 'object-cover', 'rounded-lg', 'shadow-sm', 'mt-4');
                        mainImageInput.parentElement.appendChild(newPreview);
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Preview for additional images
    const additionalImagesInput = document.getElementById('images');
    if (additionalImagesInput) {
        additionalImagesInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const previewContainer = document.createElement('div');
            previewContainer.classList.add('grid', 'grid-cols-2', 'md:grid-cols-4', 'gap-4', 'mt-4');

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewWrapper = document.createElement('div');
                    previewWrapper.classList.add('relative');
                    
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.classList.add('w-full', 'h-32', 'object-cover', 'rounded-lg', 'shadow-sm');
                    
                    previewWrapper.appendChild(preview);
                    previewContainer.appendChild(previewWrapper);
                }
                reader.readAsDataURL(file);
            });

            const existingPreview = additionalImagesInput.parentElement.nextElementSibling;
            if (existingPreview && existingPreview.classList.contains('grid')) {
                existingPreview.remove();
            }
            additionalImagesInput.parentElement.after(previewContainer);
        });
    }
});
</script>