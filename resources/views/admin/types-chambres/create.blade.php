@extends('layouts.playout')
@section('title', 'Créer un type de chambre')
@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête avec effet de gradient -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-t-xl shadow-lg p-6 mb-0">
            <div class="flex justify-between items-center">
                <h2 class="font-bold text-2xl text-white">
                    {{ __('Création d\'un nouveau type de chambre') }}
                </h2>
                <a href="{{ route('admin.types-chambres.index') }}" class="flex items-center text-white hover:text-blue-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Retour à la liste
                </a>
            </div>
            <p class="text-blue-100 mt-2">Remplissez le formulaire ci-dessous pour ajouter un nouveau type de chambre à votre hôtel.</p>
        </div>

        <!-- Card principale avec l'effet d'ombre -->
        <div class="bg-white shadow-xl rounded-b-xl p-8">
            <form action="{{ route('admin.types-chambres.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
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
                            <x-input id="nom" type="text" name="nom" :value="old('nom')" required 
                                  class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                  placeholder="Ex: Suite Deluxe" />
                            <x-input-error for="nom" class="mt-2" />
                        </div>

                        <div class="transition-all duration-200 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 rounded-lg">
                            <x-label for="etage_type" value="Type d'étage" class="text-gray-700 font-medium" />
                            <x-input id="etage_type" type="text" name="etage_type" :value="old('etage_type')" required 
                                  class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                  placeholder="Ex: Standard, Prestige, VIP" />
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
                                <x-input id="capacite_standard" type="number" name="capacite_standard" :value="old('capacite_standard')" required min="1" 
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
                                <x-input id="capacite_max" type="number" name="capacite_max" :value="old('capacite_max')" required min="1" 
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
                                <x-input id="superficie" type="number" step="0.01" name="superficie" :value="old('superficie')" required min="0" 
                                      class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">m²</span>
                                </div>
                            </div>
                            <x-input-error for="superficie" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section Description avec éditeur amélioré -->
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Description détaillée</h3>
                    </div>
                    
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <x-label for="description" value="Description du type de chambre" class="text-gray-700 font-medium p-4 border-b border-gray-200" />
                        <textarea id="description" name="description" rows="5" required 
                                 class="mt-0 block w-full border-0 focus:ring-blue-500 focus:border-blue-500 rounded-b-lg"
                                 placeholder="Décrivez en détail ce type de chambre, ses caractéristiques, son ambiance, etc.">{{ old('description') }}</textarea>
                        <x-input-error for="description" class="mt-2 px-4" />
                    </div>
                    <p class="text-sm text-gray-500 mt-2 italic">Conseil : Soyez descriptif pour aider vos clients à mieux visualiser le type de chambre.</p>
                </div>

                <!-- Section Images avec prévisualisation -->
                <div>
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 p-2 rounded-full mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Images</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-lg border border-gray-200 border-dashed hover:bg-gray-50 transition-colors">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="mt-4">
                                    <x-label for="image_principale" value="Image principale" class="text-gray-700 font-medium" />
                                    <p class="text-sm text-gray-500 mb-3">Cette image sera affichée comme principale dans les résultats de recherche</p>
                                </div>
                                <div class="mt-2">
                                    <label class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Télécharger une image
                                        <input type="file" id="image_principale" name="image_principale" required 
                                               accept="image/jpeg,image/png,image/jpg,image/gif"
                                               class="sr-only" />
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Format accepté : JPEG, PNG, JPG, GIF (max 2MB)</p>
                                <div id="preview-principale" class="mt-4 hidden">
                                    <img src="#" alt="Prévisualisation" class="mx-auto h-40 w-auto rounded-lg shadow-md" />
                                </div>
                            </div>
                            <x-input-error for="image_principale" class="mt-2" />
                        </div>

                        <div class="bg-white p-6 rounded-lg border border-gray-200 border-dashed hover:bg-gray-50 transition-colors">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="mt-4">
                                    <x-label for="images" value="Images supplémentaires" class="text-gray-700 font-medium" />
                                    <p class="text-sm text-gray-500 mb-3">Ajoutez plusieurs images pour montrer différentes vues</p>
                                </div>
                                <div class="mt-2">
                                    <label class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Télécharger des images
                                        <input type="file" id="images" name="images[]" multiple 
                                               accept="image/jpeg,image/png,image/jpg,image/gif"
                                               class="sr-only" />
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Format accepté : JPEG, PNG, JPG, GIF (max 2MB par image)</p>
                                <div id="preview-multiple" class="mt-4 hidden grid grid-cols-3 gap-2">
                                    <!-- Les prévisualisations seront ajoutées ici via JavaScript -->
                                </div>
                            </div>
                            <x-input-error for="images" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.types-chambres.index') }}" class="px-5 py-2 text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        Annuler
                    </a>
                    <x-button class="ml-4 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 transition-all px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('Créer le type de chambre') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script pour la prévisualisation des images -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prévisualisation de l'image principale
        const inputPrincipale = document.getElementById('image_principale');
        const previewPrincipale = document.getElementById('preview-principale');
        
        inputPrincipale.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewPrincipale.classList.remove('hidden');
                    previewPrincipale.querySelector('img').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Prévisualisation des images multiples
        const inputMultiple = document.getElementById('images');
        const previewMultiple = document.getElementById('preview-multiple');
        
        inputMultiple.addEventListener('change', function() {
            previewMultiple.innerHTML = '';
            previewMultiple.classList.remove('hidden');
            
            for (let i = 0; i < this.files.length; i++) {
                if (i >= 6) break; // Limiter à 6 prévisualisations pour éviter l'encombrement
                
                const reader = new FileReader();
                const imgContainer = document.createElement('div');
                const img = document.createElement('img');
                
                img.classList.add('h-20', 'w-full', 'object-cover', 'rounded');
                imgContainer.appendChild(img);
                previewMultiple.appendChild(imgContainer);
                
                reader.onload = function(e) {
                    img.src = e.target.result;
                }
                reader.readAsDataURL(this.files[i]);
            }
            
            if (this.files.length > 6) {
                const moreLabel = document.createElement('div');
                moreLabel.classList.add('text-xs', 'text-center', 'mt-2', 'text-gray-500');
                moreLabel.textContent = `+${this.files.length - 6} autres images`;
                previewMultiple.appendChild(moreLabel);
            }
        });
    });
</script>
@endsection