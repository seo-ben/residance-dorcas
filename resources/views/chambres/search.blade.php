@extends('layouts.plaout')

@section('title', 'Résultats de recherche')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Résultats de recherche</h1>

        @if($resultats->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($resultats as $chambre)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative h-48">
                            @if($chambre->medias->first())
                                <img src="{{ Storage::url($chambre->medias->first()->chemin_fichier) }}" 
                                     alt="{{ $chambre->typeChambre->nom }}"
                                     class="w-full h-full object-cover">
                            @endif
                            <div class="absolute top-0 right-0 bg-red-600 text-white px-3 py-1 m-2 rounded-full text-sm">
                                {{ number_format($chambre->prix, 0, ',', ' ') }} FCFA/nuit
                            </div>
                        </div>
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">
                                {{ $chambre->typeChambre->nom }} - Chambre {{ $chambre->numero_chambre }}
                            </h2>
                            <p class="text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                                {{ $chambre->propriete->nom }}
                            </p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($chambre->equipements->take(3) as $equipement)
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
                                        <i class="{{ $equipement->icone }} mr-1"></i>
                                        {{ $equipement->nom }}
                                    </span>
                                @endforeach
                            </div>
                            <a href="{{ route('chambres.show', $chambre->id) }}" 
                               class="block w-full bg-red-600 text-white text-center px-4 py-2 rounded-md hover:bg-red-700 transition">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $resultats->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Aucun résultat trouvé</h2>
                <p class="text-gray-600">Essayez de modifier vos critères de recherche</p>
            </div>
        @endif
    </div>
</div>
@endsection