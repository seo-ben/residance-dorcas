@extends('layouts.plaout')

@section('title', 'Nos Propriétés')

@section('content')
<div class="bg-gradient-to-b from-red-50 to-white min-h-screen py-6">
    <div class="container mx-auto px-4">
        <!-- En-tête avec titre et introduction -->
        <div class="mb-6 mt-8 text-center">
            <h1 class="text-3xl font-bold text-red-900">Nos Propriétés</h1>
            <p class="mt-2 text-gray-600 max-w-2xl mx-auto">Découvrez notre sélection d'hôtels et résidences de luxe</p>
        </div>

        <!-- Liste des propriétés -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($proprietes as $propriete)
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100">
                <!-- Image -->
                <div class="relative overflow-hidden aspect-w-16 aspect-h-10">
                    @if($propriete->medias->first())
                        <img src="{{ Storage::url($propriete->medias->first()->chemin_fichier) }}" 
                            alt="{{ $propriete->nom }}" 
                            class="w-full h-48 object-cover transform transition-transform duration-500 hover:scale-105">
                    @else
                        <div class="w-full h-48 bg-red-100 flex items-center justify-center">
                            <i class="fas fa-hotel text-red-300 text-3xl"></i>
                        </div>
                    @endif
                    
                    <!-- Badge étoiles -->
                    <div class="absolute top-2 right-2 bg-red-600 text-white px-2 py-0.5 rounded-full shadow-md text-sm">
                        @for($i = 0; $i < $propriete->etoiles; $i++)
                            <i class="fas fa-star text-yellow-300 text-xs"></i>
                        @endfor
                    </div>
                </div>

                <!-- Informations -->
                <div class="p-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $propriete->nom }}</h3>
                    
                    <p class="text-sm text-gray-600 mb-3 flex items-start">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2 mt-1"></i>
                        <span>{{ $propriete->adresse }}, {{ $propriete->ville }}, {{ $propriete->pays }}</span>
                    </p>
                    
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $propriete->description }}</p>
                    
                    <!-- Bouton voir détails -->
                    <a href="{{ route('chambres.proprietes.show', $propriete->id) }}" 
                       class="block w-full bg-red-600 text-white text-center px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-300">
                        Voir les appartement disponibles
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Aucun résultat -->
        @if($proprietes->isEmpty())
            <div class="text-center py-10 bg-white rounded-lg shadow-sm">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-500 mb-4">
                    <i class="fas fa-hotel text-2xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-800 mb-2">Aucune propriété disponible</h3>
                <p class="text-gray-500 max-w-md mx-auto">Veuillez revenir ultérieurement.</p>
            </div>
        @endif
    </div>
</div>
@endsection