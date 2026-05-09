@extends('layouts.playout')
@section('title', 'Modifier un type de chambre')
@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête avec effet de gradient -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-t-xl shadow-lg p-6 mb-0">
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-2xl text-white">
                    {{ __('Modifier le type de chambre') }}
                </h2>
                <a href="{{ route('admin.types-chambres.index') }}" class="flex items-center text-white hover:text-blue-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Retour à la liste
                </a>
            </div>
            <p class="text-blue-100 mt-2">Modifiez les informations du type de chambre ci-dessous.</p>
        </div>

        <!-- Card principale avec l'effet d'ombre -->
        <div class="bg-white shadow-xl rounded-b-xl p-8">
            <!-- Messages d'erreur et de succès -->
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Delete Image Forms -->
            @foreach($typeChambre->medias as $media)
                @if(!$media->est_couverture)
                    <form id="delete-image-{{ $media->id }}" 
                          action="{{ route('admin.types-chambres.delete-image', ['typeChambre' => $typeChambre->id, 'media' => $media->id]) }}" 
                          method="POST" 
                          class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endforeach

            <!-- Main Update Form -->
            <form action="{{ route('admin.types-chambres.update', ['typeChambre' => $typeChambre->id]) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Section Informations de base avec icône -->
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Informations de base</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="transition-all duration-200 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 rounded-lg">
                            <x-label for="nom" value="Nom du type de chambre" class="text-gray-700 font-medium" />
                            <x-input id="nom" type="text" name="nom" :value="old('nom', $typeChambre->nom)" required 
                                  class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="nom" class="mt-2" />
                        </div>

                        <div class="transition-all duration-200 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 rounded-lg">
                            <x-label for="etage_type" value="Type d'étage" class="text-gray-700 font-medium" />
                            <x-input id="etage_type" type="text" name="etage_type" :value="old('etage_type', $typeChambre->etage_type)" required 
                                  class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="etage_type" class="mt-2" />
                        </div>
                    </div>
                </div>
                
                <!-- Section Capacité et dimensions avec icône -->
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Capacité et dimensions</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <x-label for="capacite_standard" value="Capacité standard" class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <x-input id="capacite_standard" type="number" name="capacite_standard" :value="old('capacite_standard', $typeChambre->capacite_standard)" required min="1" 
                                      class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">personnes</span>
                                </div>
                            </div>
                            <x-input-error for="capacite_standard" class="mt-2" />
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <x-label for="capacite_max" value="Capacité maximale" class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <x-input id="capacite_max" type="number" name="capacite_max" :value="old('capacite_max', $typeChambre->capacite_max)" required min="1" 
                                      class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">personnes</span>
                                </div>
                            </div>
                            <x-input-error for="capacite_max" class="mt-2" />
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <x-label for="superficie" value="Superficie" class="text-gray-700 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <x-input id="superficie" type="number" step="0.01" name="superficie" :value="old('superficie', $typeChambre->superficie)" required min="0" 
                                      class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">m²</span>
                                </div>
                            </div>
                            <x-input-error for="superficie" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section Description avec icône -->
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Description</h3>
                    </div>
                    
                    <div>
                        <textarea id="description" name="description" rows="4" required 
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Décrivez les caractéristiques de ce type de chambre...">{{ old('description', $typeChambre->description) }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>
                </div>

                <!-- Section Images avec icône -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Images</h3>
                    </div>

                    <!-- Images actuelles -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Images actuelles</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($typeChambre->medias as $media)
                                <div class="relative group">
                                    <img src="{{ Storage::url($media->chemin_fichier) }}" 
                                         alt="{{ $media->titre }}" 
                                         class="w-full h-32 object-cover rounded-lg shadow-sm">
                                    @if($media->est_couverture)
                                        <span class="absolute top-2 right-2 bg-indigo-600 text-white px-2 py-1 rounded text-xs">
                                            Image principale
                                        </span>
                                    @endif
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                        @if(!$media->est_couverture)
                                            <button type="button" 
                                                    onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) document.getElementById('delete-image-{{ $media->id }}').submit();"
                                                    class="hidden group-hover:block bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition-colors">
                                                Supprimer
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Upload de nouvelles images -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nouvelle image principale</label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image_principale" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Télécharger un fichier</span>
                                            <input id="image_principale" name="image_principale" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Images supplémentaires</label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Télécharger des fichiers</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 pt-8">
                    <a href="{{ route('admin.types-chambres.index') }}" 
                       class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Mettre à jour
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
    const mainImagePreview = document.createElement('div');
    mainImagePreview.classList.add('mt-4', 'hidden');
    mainImageInput.parentElement.appendChild(mainImagePreview);

    mainImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                mainImagePreview.innerHTML = `
                    <div class="relative">
                        <img src="${e.target.result}" class="w-full h-48 object-cover rounded-lg shadow-sm" alt="Preview">
                        <button type="button" onclick="removeMainImagePreview()" 
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>`;
                mainImagePreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Preview for additional images
    const additionalImagesInput = document.getElementById('images');
    const additionalImagesPreview = document.createElement('div');
    additionalImagesPreview.classList.add('mt-4', 'grid', 'grid-cols-2', 'md:grid-cols-3', 'gap-4', 'hidden');
    additionalImagesInput.parentElement.appendChild(additionalImagesPreview);

    additionalImagesInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            additionalImagesPreview.innerHTML = '';
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'relative';
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg shadow-sm" alt="Preview ${index + 1}">
                        <button type="button" onclick="removeAdditionalImagePreview(this)" 
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>`;
                    additionalImagesPreview.appendChild(previewContainer);
                }
                reader.readAsDataURL(file);
            });
            additionalImagesPreview.classList.remove('hidden');
        }
    });
});

// Function to remove main image preview
function removeMainImagePreview() {
    const mainImageInput = document.getElementById('image_principale');
    const mainImagePreview = mainImageInput.parentElement.querySelector('div.mt-4');
    mainImageInput.value = '';
    mainImagePreview.classList.add('hidden');
    mainImagePreview.innerHTML = '';
}

// Function to remove specific additional image preview
function removeAdditionalImagePreview(button) {
    const previewContainer = button.closest('.relative');
    previewContainer.remove();
    
    // If no more previews, hide the container
    const additionalImagesPreview = document.querySelector('#images').parentElement.querySelector('.grid');
    if (additionalImagesPreview.children.length === 0) {
        additionalImagesPreview.classList.add('hidden');
    }
}
</script>
