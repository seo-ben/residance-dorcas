@extends('layouts.playout')
@section('content')
<div class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête de la page -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                {{ __('Ajouter une nouvelle propriété') }}
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Complétez le formulaire ci-dessous pour ajouter une nouvelle propriété
            </p>
        </div>

        <!-- Carte principale du formulaire -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Barre de progression -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2"></div>
            
            <!-- Formulaire -->
            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Des erreurs ont été détectées dans votre formulaire
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

                <form action="{{ route('admin.proprietes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Section 1: Informations générales -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded-full mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            </span>
                            Informations générales
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la propriété *</label>
                                <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required 
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md transition" 
                                    placeholder="Hôtel Magnifique">
                            </div>

                            <div class="space-y-2">
                                <label for="etoiles" class="block text-sm font-medium text-gray-700">Classification *</label>
                                <div class="relative">
                                    <select id="etoiles" name="etoiles" required 
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('etoiles') == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'étoile' : 'étoiles' }}
                                            </option>
                                        @endfor
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Adresse et localisation -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded-full mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Adresse et localisation
                        </h2>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse complète *</label>
                                <input id="adresse" type="text" name="adresse" value="{{ old('adresse') }}" required 
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="123 Avenue des Hôteliers">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label for="ville" class="block text-sm font-medium text-gray-700">Ville *</label>
                                    <input id="ville" type="text" name="ville" value="Lomé" required 
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Lome">
                                </div>

                                <div class="space-y-2">
                                    <label for="code_postal" class="block text-sm font-medium text-gray-700">Code postal </label>
                                    <input id="code_postal" type="text" name="code_postal" value="{{ old('code_postal') }}" 
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="75001">
                                </div>

                                <div class="space-y-2">
                                    <label for="pays" class="block text-sm font-medium text-gray-700">Pays </label>
                                    <input id="pays" type="text" name="pays" value="Togo"  
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Togo">
                                </div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-md">
                                <h3 class="text-sm font-medium text-blue-800 mb-2">Coordonnées GPS</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                        <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude') }}" 
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            placeholder="48.858844">
                                        <p class="text-xs text-gray-500">Optionnel - Utilisé pour le placement sur la carte</p>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                        <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude') }}" 
                                            class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            placeholder="2.294351">
                                        <p class="text-xs text-gray-500">Optionnel - Utilisé pour le placement sur la carte</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Contact -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded-full mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                            </span>
                            Informations de contact
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" required
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="+33 1 23 45 67 89">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="contact@hotel-magnifique.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Description -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded-full mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Description de la propriété
                        </h2>
                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description détaillée
                            </label>
                            <textarea id="description" name="description" rows="5" 
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                placeholder="Décrivez votre propriété, ses caractéristiques, son ambiance...">{{ old('description') }}</textarea>
                            <p class="text-xs text-gray-500">
                                Donnez aux clients toutes les informations qui pourraient les intéresser. Soyez descriptif et attrayant.
                            </p>
                        </div>
                    </div>

                    <!-- Section 5: Statut -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded-full mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Statut de la propriété
                        </h2>
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700">
                                État de publication *
                            </label>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="statut-actif" name="statut" type="radio" value="actif" {{ old('statut', 'actif') == 'actif' ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="statut-actif" class="ml-3 block text-sm font-medium text-gray-700">
                                        Actif - Visible sur le site
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="statut-inactif" name="statut" type="radio" value="inactif" {{ old('statut') == 'inactif' ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="statut-inactif" class="ml-3 block text-sm font-medium text-gray-700">
                                        Inactif - Non visible sur le site
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Images -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded-full mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Images de la propriété
                        </h2>
                        
                        <!-- Image principale -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700">
                                Image principale *
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <div id="main-image-preview" class="hidden mb-3">
                                        <img src="" alt="Image preview" class="mx-auto h-32 w-auto">
                                    </div>
                                    <div id="main-image-placeholder">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image_principale" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Télécharger un fichier</span>
                                            <input id="image_principale" name="image_principale" type="file" accept="image/*" required class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, JPEG jusqu'à 2MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Images supplémentaires -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Images supplémentaires
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center w-full">
                                    <div id="additional-images-preview" class="hidden mb-3 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    </div>
                                    <div id="additional-images-placeholder">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Télécharger des fichiers</span>
                                            <input id="images" name="images[]" type="file" accept="image/*" multiple class="sr-only">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, JPEG jusqu'à 2MB par image
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="pt-5 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.proprietes.index') }}" 
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" 
                                class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Créer la propriété
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card d'aide -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6 shadow-sm">
            <h3 class="text-lg font-medium text-blue-800 mb-2">Conseils pour créer une propriété</h3>
            <div class="text-blue-700 space-y-2">
                <p>• Utilisez des photos de haute qualité pour présenter votre propriété sous son meilleur jour</p>
                <p>• Rédigez une description complète et attrayante qui mentionne les points forts</p>
                <p>• Vérifiez que toutes les informations de contact sont correctes et à jour</p>
                <p>• Ajoutez les coordonnées GPS exactes pour faciliter la localisation sur la carte</p>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Main image preview
    const mainImageInput = document.getElementById('image_principale');
    const mainImagePreview = document.getElementById('main-image-preview');
    const mainImagePlaceholder = document.getElementById('main-image-placeholder');

    mainImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                mainImagePreview.innerHTML = `
                    <div class="relative">
                        <img src="${e.target.result}" alt="Image preview" class="mx-auto h-32 w-auto">
                        <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none" onclick="removeMainImage()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>`;
                mainImagePreview.classList.remove('hidden');
                mainImagePlaceholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Function to remove main image
    window.removeMainImage = function() {
        mainImageInput.value = '';
        mainImagePreview.classList.add('hidden');
        mainImagePlaceholder.classList.remove('hidden');
    }

    // Additional images preview
    const additionalImagesInput = document.getElementById('images');
    const additionalImagesPreview = document.getElementById('additional-images-preview');
    const additionalImagesPlaceholder = document.getElementById('additional-images-placeholder');

    additionalImagesInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        updateAdditionalImagesPreviews(files);
    });

    function updateAdditionalImagesPreviews(files) {
        if (files.length > 0) {
            additionalImagesPreview.classList.remove('hidden');
            additionalImagesPlaceholder.classList.add('hidden');
            
            // Create a new DataTransfer object
            const dataTransfer = new DataTransfer();
            
            // Add all files to the DataTransfer object
            files.forEach(file => dataTransfer.items.add(file));
            
            // Update the input files
            additionalImagesInput.files = dataTransfer.files;
            
            // Clear and rebuild previews
            additionalImagesPreview.innerHTML = '';
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-w-1 aspect-h-1';
                    div.innerHTML = `
                        <div class="relative group">
                            <img src="${e.target.result}" alt="Preview" class="object-cover rounded-lg shadow-sm h-32 w-full">
                            <button type="button" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none" 
                                    onclick="removeAdditionalImage(${index})">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>`;
                    additionalImagesPreview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        } else {
            additionalImagesPreview.classList.add('hidden');
            additionalImagesPlaceholder.classList.remove('hidden');
            additionalImagesPreview.innerHTML = '';
        }
    }

    // Function to remove additional image
    window.removeAdditionalImage = function(index) {
        const files = Array.from(additionalImagesInput.files);
        files.splice(index, 1);
        updateAdditionalImagesPreviews(files);
    }

    // Drag and drop functionality
    const dropZones = document.querySelectorAll('.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZones.forEach(zone => {
            zone.addEventListener(eventName, preventDefaults, false);
        });
    });

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZones.forEach(zone => {
            zone.addEventListener(eventName, highlight, false);
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZones.forEach(zone => {
            zone.addEventListener(eventName, unhighlight, false);
        });
    });

    function highlight(e) {
        e.target.closest('.border-dashed').classList.add('border-blue-400', 'bg-blue-50');
    }

    function unhighlight(e) {
        e.target.closest('.border-dashed').classList.remove('border-blue-400', 'bg-blue-50');
    }

    dropZones[0].addEventListener('drop', handleMainImageDrop, false);
    dropZones[1].addEventListener('drop', handleAdditionalImagesDrop, false);

    function handleMainImageDrop(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];
        mainImageInput.files = dt.files;
        mainImageInput.dispatchEvent(new Event('change'));
    }

    function handleAdditionalImagesDrop(e) {
        const dt = e.dataTransfer;
        additionalImagesInput.files = dt.files;
        additionalImagesInput.dispatchEvent(new Event('change'));
    }
});
</script>
