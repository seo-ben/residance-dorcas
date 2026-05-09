@extends('layouts.playout')

@section('title', 'Modifier le Véhicule')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="mb-8">
            <a href="{{ route('admin.vehicules.index') }}" class="text-sm font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Retour au parc
            </a>
            <h1 class="text-3xl font-black text-gray-900">Modifier : {{ $vehicule->marque }} {{ $vehicule->modele }}</h1>
            <p class="text-gray-500">Mettez à jour les informations du véhicule.</p>
        </header>

        <form action="{{ route('admin.vehicules.update', $vehicule) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Informations de base -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-red-600"></i>
                    Informations Générales
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="marque" class="block text-sm font-semibold text-gray-700">Marque</label>
                        <input type="text" name="marque" id="marque" value="{{ $vehicule->marque }}" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label for="modele" class="block text-sm font-semibold text-gray-700">Modèle</label>
                        <input type="text" name="modele" id="modele" value="{{ $vehicule->modele }}" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label for="immatriculation" class="block text-sm font-semibold text-gray-700">Immatriculation</label>
                        <input type="text" name="immatriculation" id="immatriculation" value="{{ $vehicule->immatriculation }}" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-gray-700">Type de véhicule</label>
                        <select name="type" id="type" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            @foreach(['Berline', 'SUV', 'Citadine', 'Luxe', 'Utilitaire'] as $type)
                                <option value="{{ $type }}" {{ $vehicule->type == $type ? 'selected' : '' }}>{{ $type === 'SUV' ? 'SUV / 4x4' : $type }}</option>
                            @endforeach
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
                            <option value="Automatique" {{ $vehicule->transmission == 'Automatique' ? 'selected' : '' }}>Automatique</option>
                            <option value="Manuelle" {{ $vehicule->transmission == 'Manuelle' ? 'selected' : '' }}>Manuelle</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="carburant" class="block text-sm font-semibold text-gray-700">Carburant</label>
                        <select name="carburant" id="carburant" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            @foreach(['Essence', 'Diesel', 'Hybride', 'Électrique'] as $carb)
                                <option value="{{ $carb }}" {{ $vehicule->carburant == $carb ? 'selected' : '' }}>{{ $carb }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="nb_places" class="block text-sm font-semibold text-gray-700">Nombre de places</label>
                        <input type="number" name="nb_places" id="nb_places" value="{{ $vehicule->nb_places }}" min="1" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Options & Équipements</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php $currentOptions = $vehicule->caracteristiques ?? []; @endphp
                        @foreach(['Climatisation', 'GPS', 'Bluetooth', 'Siège Bébé', 'Toit Ouvrant', 'Caméra de recul', 'Cuir', '4x4'] as $option)
                            <label class="flex items-center space-x-3 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="caracteristiques[]" value="{{ $option }}" {{ in_array($option, $currentOptions) ? 'checked' : '' }} class="rounded text-red-600 focus:ring-red-500">
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
                        <input type="number" name="prix_journalier" id="prix_journalier" value="{{ $vehicule->prix_journalier }}" required step="0.01" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label for="statut" class="block text-sm font-semibold text-gray-700">Statut</label>
                        <select name="statut" id="statut" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            @foreach(['disponible', 'loue', 'maintenance', 'indisponible'] as $stat)
                                <option value="{{ $stat }}" {{ $vehicule->statut == $stat ? 'selected' : '' }}>{{ ucfirst($stat) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700">Description / Notes</label>
                    <textarea name="description" id="description" rows="4" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">{{ $vehicule->description }}</textarea>
                </div>
            </div>

            <!-- Gestion des Images Existantes -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-images mr-3 text-red-600"></i>
                    Gestion des Photos
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    @foreach($vehicule->images as $image)
                        <div class="relative group rounded-2xl overflow-hidden border border-gray-100 shadow-sm aspect-video">
                            <img src="{{ asset('storage/' . $image->chemin_image) }}" class="w-full h-full object-cover">
                            
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center space-y-2 p-2">
                                @if(!$image->est_principale)
                                    <form action="{{ route('admin.vehicules.images.primary', [$vehicule, $image]) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full py-1.5 bg-white/20 hover:bg-white/40 text-white text-xs font-bold rounded-lg backdrop-blur-sm transition-colors">
                                            Rendre Principale
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.vehicules.images.delete', $image) }}" method="POST" class="w-full" onsubmit="return confirm('Supprimer cette image ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-1.5 bg-red-600/80 hover:bg-red-600 text-white text-xs font-bold rounded-lg backdrop-blur-sm transition-colors">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                            
                            @if($image->est_principale)
                                <div class="absolute top-2 left-2 px-2 py-1 bg-red-600 text-white text-[10px] font-bold rounded-md shadow-lg">
                                    PRINCIPALE
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700">Ajouter de nouvelles photos</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="new_images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-3xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                <p class="text-xs text-gray-500">Ajouter des images supplémentaires</p>
                            </div>
                            <input id="new_images" name="new_images[]" type="file" multiple class="hidden" accept="image/*" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="history.back()" class="px-8 py-4 bg-gray-100 text-gray-700 font-bold rounded-2xl hover:bg-gray-200 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-10 py-4 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                    Mettre à jour le Véhicule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
