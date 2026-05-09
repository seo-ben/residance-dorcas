@extends('layouts.playout')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('admin.chambres.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Informations de base -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="numero_chambre" value="Numéro de chambre" />
                            <x-input id="numero_chambre" type="text" name="numero_chambre" :value="old('numero_chambre')" required class="mt-1 block w-full" />
                            <x-input-error for="numero_chambre" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="etage" value="Étage" />
                            <x-input id="etage" type="number" name="etage" :value="old('etage')" required class="mt-1 block w-full" />
                            <x-input-error for="etage" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="id_type_chambre" value="Type de chambre" />
                            <select id="id_type_chambre" name="id_type_chambre" required class="mt-1 p-2 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value="">Sélectionnez un type de chambre</option>
                                @foreach($typeappartement as $type)
                                    <option value="{{ $type->id }}" {{ old('id_type_chambre') == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="id_type_chambre" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="id_propriete" value="Propriété" />
                            <select id="id_propriete" name="id_propriete" required class="mt-1 p-2 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value="">Sélectionnez une propriété</option>
                                @foreach($proprietes as $propriete)
                                    <option value="{{ $propriete->id }}" {{ old('id_propriete') == $propriete->id ? 'selected' : '' }}>
                                        {{ $propriete->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="id_propriete" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="prix_base" value="Prix de base" />
                            <x-input id="prix_base" type="number" step="0.01" name="prix_base" :value="old('prix_base')" required class="mt-1 block w-full" />
                            <x-input-error for="prix_base" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="statut" value="Statut" />
                            <select id="statut" name="statut" required class="mt-1 p-2 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                <option value="">Sélectionnez un statut</option>
                                <option value="disponible" {{ old('statut') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="occupee" {{ old('statut') == 'occupee' ? 'selected' : '' }}>Occupée</option>
                                <option value="maintenance" {{ old('statut') == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                                <option value="inactive" {{ old('statut') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error for="statut" class="mt-2" />
                        </div>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Équipements</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($equipements as $equipement)
                                <div class="flex items-center space-x-2 p-2 border rounded-md">
                                    <input type="checkbox" 
                                        name="equipements[]" 
                                        value="{{ $equipement->id }}" 
                                        id="equipement_{{ $equipement->id }}"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="equipement_{{ $equipement->id }}" class="flex-grow">
                                        {{ $equipement->nom }}
                                    </label>
                                    <input type="number" 
                                        name="quantites[{{ $equipement->id }}]" 
                                        value="1"
                                        min="1"
                                        class="w-20 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="mt-6">
                        <div class="mb-4">
                            <x-label for="image_principale" value="Image principale" />
                            <input type="file" id="image_principale" name="image_principale" accept="image/*" required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            <x-input-error for="image_principale" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="images" value="Images supplémentaires" />
                            <input type="file" id="images" name="images[]" accept="image/*" multiple
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            <x-input-error for="images" class="mt-2" />
                        </div>
                    </div>

                  
                    <!-- Notes -->
                    <div class="mt-6">
                        <x-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        <x-input-error for="notes" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.chambres.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Annuler</a>
                        <x-button>
                            {{ __('Créer la chambre') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection