@extends('layouts.playout')

@section('title', 'Ajouter un Véhicule')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="mb-8">
            <a href="{{ route('admin.vehicules.index') }}" class="text-sm font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Retour au parc
            </a>
            <h1 class="text-3xl font-black text-gray-900">Nouveau Véhicule</h1>
            <p class="text-gray-500">Enregistrez un nouveau véhicule dans votre flotte.</p>
        </header>

        <form action="{{ route('admin.vehicules.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Informations de base -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-red-600"></i>
                    Informations Générales
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="marque" class="block text-sm font-semibold text-gray-700">Marque</label>
                        <input type="text" name="marque" id="marque" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors" placeholder="Ex: Toyota">
                    </div>
                    <div class="space-y-2">
                        <label for="modele" class="block text-sm font-semibold text-gray-700">Modèle</label>
                        <input type="text" name="modele" id="modele" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors" placeholder="Ex: Land Cruiser">
                    </div>
                    <div class="space-y-2">
                        <label for="immatriculation" class="block text-sm font-semibold text-gray-700">Immatriculation</label>
                        <input type="text" name="immatriculation" id="immatriculation" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors" placeholder="Ex: TG-1234-AX">
                    </div>
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-gray-700">Type de véhicule</label>
                        <select name="type" id="type" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            <option value="Berline">Berline</option>
                            <option value="SUV">SUV / 4x4</option>
                            <option value="Citadine">Citadine</option>
                            <option value="Luxe">Luxe / Prestige</option>
                            <option value="Utilitaire">Utilitaire</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Caractéristiques Techniques -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-cogs mr-3 text-red-600"></i>
                    Caractéristiques Techniques
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label for="transmission" class="block text-sm font-semibold text-gray-700">Transmission</label>
                        <select name="transmission" id="transmission" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            <option value="Automatique">Automatique</option>
                            <option value="Manuelle">Manuelle</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="carburant" class="block text-sm font-semibold text-gray-700">Carburant</label>
                        <select name="carburant" id="carburant" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            <option value="Essence">Essence</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Hybride">Hybride</option>
                            <option value="Électrique">Électrique</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="nb_places" class="block text-sm font-semibold text-gray-700">Nombre de places</label>
                        <input type="number" name="nb_places" id="nb_places" value="5" min="1" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Options & Équipements</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(['Climatisation', 'GPS', 'Bluetooth', 'Siège Bébé', 'Toit Ouvrant', 'Caméra de recul', 'Cuir', '4x4'] as $option)
                            <label class="flex items-center space-x-3 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="caracteristiques[]" value="{{ $option }}" class="rounded text-red-600 focus:ring-red-500">
                                <span class="text-sm text-gray-600">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Prix et Statut -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-tags mr-3 text-red-600"></i>
                    Tarification & Statut
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="prix_journalier" class="block text-sm font-semibold text-gray-700">Prix par Jour (FCFA)</label>
                        <input type="number" name="prix_journalier" id="prix_journalier" required step="0.01" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors" placeholder="Ex: 25000">
                    </div>
                    <div class="space-y-2">
                        <label for="statut" class="block text-sm font-semibold text-gray-700">Statut Initial</label>
                        <select name="statut" id="statut" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            <option value="disponible">Disponible</option>
                            <option value="loue">Déjà Loué</option>
                            <option value="maintenance">En Maintenance</option>
                            <option value="indisponible">Indisponible</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700">Description / Notes</label>
                    <textarea name="description" id="description" rows="4" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors" placeholder="Détails supplémentaires sur le véhicule..."></textarea>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-images mr-3 text-red-600"></i>
                    Photos du Véhicule
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-center w-full">
                        <label for="images" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-3xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-bold text-red-600">Cliquez pour uploader</span> ou glissez-déposez</p>
                                <p class="text-xs text-gray-400">PNG, JPG, WEBP (Max. 5Mo par image)</p>
                            </div>
                            <input id="images" name="images[]" type="file" multiple required class="hidden" accept="image/*" />
                        </label>
                    </div>
                    <p class="text-xs text-gray-400"><i class="fas fa-info-circle mr-1"></i> La première image sera définie comme image principale par défaut.</p>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="history.back()" class="px-8 py-4 bg-gray-100 text-gray-700 font-bold rounded-2xl hover:bg-gray-200 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-10 py-4 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                    Enregistrer le Véhicule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
