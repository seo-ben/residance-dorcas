@extends('layouts.playout')
@section('title', 'Détails du type de chambre')
@section('content')
<div class="bg-gray-50 py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête avec actions -->
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="bg-white shadow-sm rounded-lg px-6 py-4 border-l-4 border-indigo-600">
                <h1 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ $typeChambre->nom }}
                </h1>
                {{-- <p class="text-gray-500 mt-1">Référence: #{{ $typeChambre->id }}</p> --}}
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.types-chambres.index') }}" 
                   class="flex items-center bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la liste
                </a>
                <a href="{{ route('admin.types-chambres.edit', ['typeChambre' => $typeChambre->id]) }}" 
                   class="flex items-center bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne de gauche -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Galerie d'images -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                        <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Galerie Photos
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($typeChambre->medias->count() > 0)
                            <!-- Image principale -->
                            <div class="mb-4">
                                @php
                                    $coverImage = $typeChambre->medias->firstWhere('est_couverture', true) ?? $typeChambre->medias->first();
                                @endphp
                                <div class="relative rounded-lg overflow-hidden shadow-lg">
                                    <img src="{{ Storage::url($coverImage->chemin_fichier) }}" 
                                         alt="{{ $typeChambre->nom }}"
                                         class="w-full h-80 object-cover">
                                    @if($coverImage->est_couverture)
                                        <div class="absolute top-4 right-4 bg-indigo-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-md">
                                            Image principale
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Galerie miniatures -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($typeChambre->medias as $media)
                                    @if(!($media->est_couverture))
                                        <div class="relative group rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                            <img src="{{ Storage::url($media->chemin_fichier) }}" 
                                                 alt="{{ $typeChambre->nom }}"
                                                 class="w-full h-24 object-cover">
                                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 bg-black bg-opacity-30 transition-opacity duration-300"></div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-4 text-gray-500 font-medium">Aucune image disponible pour ce type de chambre</p>
                                <p class="mt-2 text-gray-400 text-sm">Les photos seront affichées ici une fois téléchargées</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                        <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Description
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($typeChambre->description)
                            <div class="prose max-w-none text-gray-600">
                                <p>{{ $typeChambre->description }}</p>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-500">
                                Aucune description disponible
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne de droite -->
            <div class="space-y-8">
                <!-- Statistiques principales -->
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 overflow-hidden shadow-lg rounded-lg text-white">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="font-bold text-xl">Vue d'ensemble</h2>
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $typeChambre->appartement->count() > 0 ? 'bg-green-500 bg-opacity-20' : 'bg-yellow-500 bg-opacity-20' }}">
                                {{ $typeChambre->appartement->count() > 0 ? 'En utilisation' : 'Non utilisé' }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white bg-opacity-10 p-4 rounded-lg">
                                <div class="text-xs text-indigo-100 uppercase">Capacité std.</div>
                                <div class="flex items-end mt-1">
                                    <span class="text-3xl font-bold">{{ $typeChambre->capacite_standard }}</span>
                                    <span class="ml-1 text-xs opacity-75">personnes</span>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-10 p-4 rounded-lg">
                                <div class="text-xs text-indigo-100 uppercase">Capacité max.</div>
                                <div class="flex items-end mt-1">
                                    <span class="text-3xl font-bold">{{ $typeChambre->capacite_max }}</span>
                                    <span class="ml-1 text-xs opacity-75">personnes</span>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-10 p-4 rounded-lg">
                                <div class="text-xs text-indigo-100 uppercase">Surface</div>
                                <div class="flex items-end mt-1">
                                    <span class="text-3xl font-bold">{{ $typeChambre->superficie }}</span>
                                    <span class="ml-1 text-xs opacity-75">m²</span>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-10 p-4 rounded-lg">
                                <div class="text-xs text-indigo-100 uppercase">appartement</div>
                                <div class="flex items-end mt-1">
                                    <span class="text-3xl font-bold">{{ $typeChambre->appartement->count() }}</span>
                                    <span class="ml-1 text-xs opacity-75">au total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations détaillées -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                        <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Détails techniques
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center py-3 border-b border-gray-100">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-gray-500 text-sm">Nom du type</span>
                                    <p class="text-gray-800 font-medium">{{ $typeChambre->nom }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center py-3 border-b border-gray-100">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-gray-500 text-sm">Étage type</span>
                                    <p class="text-gray-800 font-medium">{{ $typeChambre->etage_type ?: 'Non spécifié' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions supplémentaires -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                        <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Actions rapides
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 gap-3">
                            <a href="{{ route('admin.chambres.create', ['type_id' => $typeChambre->id]) }}" class="flex items-center justify-between bg-gray-50 hover:bg-gray-100 p-3 rounded-lg border border-gray-200 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Ajouter une chambre</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                                                        
                            <a href="{{ route('admin.chambres.index', ['type_id' => $typeChambre->nom]) }}" class="flex items-center justify-between bg-gray-50 hover:bg-gray-100 p-3 rounded-lg border border-gray-200 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Voir toutes les appartement</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection