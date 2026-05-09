@extends('layouts.playout')
@section('title', 'Modifier une chambre')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modifier la Chambre') }} {{ $chambre->numero_chambre }}
            </h2>
            <a href="{{ route('admin.chambres.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Retour à la liste
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.chambres.update', $chambre) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Informations de base -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-label for="numero_chambre" value="Numéro de chambre" />
                            <x-input id="numero_chambre" type="text" name="numero_chambre" :value="old('numero_chambre', $chambre->numero_chambre)" required class="mt-1 block w-full" />
                            <x-input-error for="numero_chambre" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="etage" value="Étage" />
                            <x-input id="etage" type="number" name="etage" :value="old('etage', $chambre->etage)" required class="mt-1 block w-full" />
                            <x-input-error for="etage" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="id_type_chambre" value="Type de chambre" />
                            <select id="id_type_chambre" name="id_type_chambre" required class="mt-1 p-2 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                @foreach($typeappartement as $type)
                                    <option value="{{ $type->id }}" {{ (old('id_type_chambre', $chambre->id_type_chambre) == $type->id) ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="id_type_chambre" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="id_propriete" value="Propriété" />
                            <select id="id_propriete" name="id_propriete" required class="mt-1 block p-2 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                @foreach($proprietes as $propriete)
                                    <option value="{{ $propriete->id }}" {{ (old('id_propriete', $chambre->id_propriete) == $propriete->id) ? 'selected' : '' }}>
                                        {{ $propriete->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="id_propriete" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="prix_base" value="Prix de base" />
                            <x-input id="prix_base" type="number" step="0.01" name="prix_base" :value="old('prix_base', $chambre->prix_base)" required class="mt-1 block w-full" />
                            <x-input-error for="prix_base" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="statut" value="Statut" />
                            <select id="statut" name="statut" required class="mt-1 p-2 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value="disponible" {{ (old('statut', $chambre->statut) == 'disponible') ? 'selected' : '' }}>Disponible</option>
                                <option value="occupee" {{ (old('statut', $chambre->statut) == 'occupee') ? 'selected' : '' }}>Occupée</option>
                                <option value="maintenance" {{ (old('statut', $chambre->statut) == 'maintenance') ? 'selected' : '' }}>En maintenance</option>
                                <option value="inactive" {{ (old('statut', $chambre->statut) == 'inactive') ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error for="statut" class="mt-2" />
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Images</h3>
                        
                        <!-- Image principale -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Image principale</h4>
                            @if($mainImage = $chambre->medias->where('est_couverture', true)->first())
                                <div class="mb-4">
                                    <div class="relative inline-block group">
                                        <img src="{{ Storage::url($mainImage->chemin_fichier) }}" 
                                             alt="Image principale" 
                                             class="w-48 h-48 object-cover rounded-lg">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                            <button type="button" 
                                                    onclick="document.getElementById('delete-main-image-{{ $mainImage->id }}').submit();"
                                                    class="hidden group-hover:block bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <input type="file" 
                                   name="image_principale" 
                                   accept="image/*"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <p class="mt-1 text-sm text-gray-500">Format accepté : JPEG, PNG, JPG, GIF (max 2MB)</p>
                        </div>

                        <!-- Images supplémentaires -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Images supplémentaires</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @foreach($chambre->medias->where('est_couverture', false) as $image)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($image->chemin_fichier) }}" 
                                             alt="Image supplémentaire"
                                             class="w-full h-32 object-cover rounded-lg">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                            <button type="button" 
                                                    onclick="document.getElementById('delete-image-{{ $image->id }}').submit();"
                                                    class="hidden group-hover:block bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="file" 
                                   name="images[]" 
                                   multiple 
                                   accept="image/*"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <p class="mt-1 text-sm text-gray-500">Format accepté : JPEG, PNG, JPG, GIF (max 2MB par image)</p>
                        </div>
                    </div>

                    <!-- Équipements -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Équipements</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($equipements as $equipement)
                                @php
                                    $existingEquipement = $chambre->equipements->where('id', $equipement->id)->first();
                                @endphp
                                <div class="flex items-center space-x-2 p-2 border rounded-md">
                                    <input type="checkbox" 
                                        name="equipements[]" 
                                        value="{{ $equipement->id }}" 
                                        id="equipement_{{ $equipement->id }}"
                                        {{ $existingEquipement ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="equipement_{{ $equipement->id }}" class="flex-grow">
                                        {{ $equipement->nom }}
                                    </label>
                                    <input type="number" 
                                        name="quantites[{{ $equipement->id }}]" 
                                        value="{{ $existingEquipement ? $existingEquipement->pivot->quantite : 1 }}"
                                        min="1"
                                        class="w-20 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                        <textarea name="notes" 
                                  rows="4" 
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('notes', $chambre->notes) }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.chambres.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:shadow-outline-gray disabled:opacity-25 transition">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                            Mettre à jour
                        </button>
                    </div>
                </form>

                <!-- Formulaires de suppression séparés -->
                @if($mainImage)
                    <form id="delete-main-image-{{ $mainImage->id }}" 
                          action="{{ route('admin.chambres.media.delete', $mainImage->id) }}" 
                          method="POST" 
                          class="hidden"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette image principale ?');">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif

                @foreach($chambre->medias->where('est_couverture', false) as $image)
                    <form id="delete-image-{{ $image->id }}" 
                          action="{{ route('admin.chambres.media.delete', $image->id) }}" 
                          method="POST" 
                          class="hidden"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette image ?');">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
