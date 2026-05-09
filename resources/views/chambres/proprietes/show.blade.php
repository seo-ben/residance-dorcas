@extends('layouts.plaout')

@section('title', $propriete->nom)

@section('content')
<div class="bg-gradient-to-b from-red-100 to-white py-10">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- En-tête avec navigation -->
        <div class="mb-8 mt-6">
            <a href="{{ route('chambres.proprietes.index') }}" class="inline-flex items-center gap-2 text-red-700 hover:text-red-900 font-medium transition-colors duration-300 group mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour aux propriétés
            </a>
            
            <!-- Carte principale de la propriété -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <!-- Galerie d'images principale -->
                <div class="relative h-[28rem] bg-gray-100">
                    @if($propriete->medias->isNotEmpty())
                        <div class="swiper-container h-full">
                            <div class="swiper-wrapper">
                                @foreach($propriete->medias as $media)
                                <div class="swiper-slide">
                                    <img src="{{ Storage::url($media->chemin_fichier) }}" 
                                        alt="{{ $propriete->nom }}" 
                                        class="w-full h-full object-cover">
                                </div>
                                @endforeach
                            </div>
                            <!-- Contrôles du slider améliorés -->
                            <div class="swiper-pagination !bottom-4"></div>
                            <div class="swiper-button-next !right-6 !w-12 !h-12 rounded-full bg-white/70 backdrop-blur-sm shadow-md after:text-red-600"></div>
                            <div class="swiper-button-prev !left-6 !w-12 !h-12 rounded-full bg-white/70 backdrop-blur-sm shadow-md after:text-red-600"></div>
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badge étoiles -->
                    <div class="absolute top-6 right-6 bg-white/90 backdrop-blur-sm text-red-800 px-4 py-2 rounded-xl shadow-lg">
                        @for($i = 0; $i < $propriete->etoiles; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                </div>
                
               
            </div>
             <!-- Informations de la propriété -->
             <div class="p-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ $propriete->nom }}</h1>
                
                <p class="text-gray-700 mb-6 flex items-start text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 mr-3 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $propriete->adresse }}, {{ $propriete->ville }}, {{ $propriete->pays }}</span>
                </p>
                
                <div class="flex flex-wrap gap-8 mb-8 text-gray-700">
                    <a href="tel:{{ $propriete->telephone }}" class="flex items-center hover:text-red-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $propriete->telephone }}</span>
                    </a>
                    <a href="mailto:{{ $propriete->email }}" class="flex items-center hover:text-red-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $propriete->email }}</span>
                    </a>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">À propos de cette propriété</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $propriete->description }}</p>
                </div>
            </div>
        </div>

        <!-- Galerie photos -->
        @if($propriete->medias->count() > 1)
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Galerie de la propriéte
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($propriete->medias as $media)
                <div class="group relative overflow-hidden rounded-xl aspect-square shadow-md hover:shadow-xl transition-all duration-500 cursor-pointer">
                    <img src="{{ Storage::url($media->chemin_fichier) }}" 
                         alt="{{ $propriete->nom }}" 
                         class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- appartement disponibles -->
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                appartement disponibles
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($appartement as $chambre)
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col group">
                    <!-- Image avec overlay -->
                    <div class="relative overflow-hidden">
                        @if($chambre->medias->first())
                            <img src="{{ Storage::url($chambre->medias->first()->chemin_fichier) }}" 
                                alt="{{ $chambre->typeChambre->nom }}" 
                                class="w-full h-64 object-cover transform transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-red-50 to-red-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                        @endif
                        
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm text-red-600 px-4 py-2 rounded-lg shadow-lg font-bold border border-red-50">
                                    {{ number_format($chambre->prix_base, 0, ',', ' ') }} FCFA
                                </div>
                        
                        <!-- Status tag -->
                        <div class="absolute bottom-4 left-4">
                            <span class="{{ $chambre->statut == 'disponible' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }} text-xs font-semibold px-3 py-1.5 rounded-lg uppercase tracking-wider shadow-sm">
                                {{ ucfirst($chambre->statut) }}
                            </span>
                        </div>
                    </div>

                    <!-- Informations -->
                    <div class="p-6 flex-grow flex flex-col">
                        <!-- En-tête de chambre -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">
                                    {{ $chambre->typeChambre->nom }} - Chambre {{ $chambre->numero_chambre }}
                                </h3>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i <= $chambre->note_moyenne ? 'text-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-xs font-semibold text-red-700">
                                        {{ number_format($chambre->note_moyenne, 1) }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-gray-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                </svg>
                                {{ $chambre->capacite }} personne(s)
                            </p>
                        </div>
                        
                        <!-- Équipements -->
                        <div class="flex flex-wrap gap-2 mb-5">
                            @foreach($chambre->equipements->take(3) as $equipement)
                                <span class="bg-red-50 text-red-700 text-xs font-medium px-2.5 py-1.5 rounded-lg flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $equipement->nom }}
                                </span>
                            @endforeach
                            @if($chambre->equipements->count() > 3)
                                <span class="bg-gray-100 text-gray-700 text-xs font-medium px-2.5 py-1.5 rounded-lg flex items-center cursor-pointer hover:bg-gray-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    {{ $chambre->equipements->count() - 3 }} plus
                                </span>
                            @endif
                        </div>
                        
                        <!-- Miniatures d'images améliorées -->
                        <div class="flex mb-6">
                            <div class="flex -space-x-3">
                                @foreach($chambre->medias->take(3) as $media)
                                    <div class="w-12 h-12 rounded-lg overflow-hidden ring-2 ring-white shadow-sm">
                                        <img src="{{ Storage::url($media->chemin_fichier) }}" 
                                            alt="Thumbnail" 
                                            class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                                @if($chambre->medias->count() > 3)
                                    <div class="w-12 h-12 rounded-lg overflow-hidden ring-2 ring-white shadow-sm bg-gray-100 flex items-center justify-center">
                                        <span class="text-xs font-semibold text-red-600">+{{ $chambre->medias->count() - 3 }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Bouton d'action -->
                        <div class="mt-auto">
                            <a href="{{ route('chambres.show', $chambre->id) }}" 
                            class="flex items-center justify-center w-full bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-3 rounded-xl transition-colors duration-300 focus:ring-4 focus:ring-red-300 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Demander une visite
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Message si aucune chambre -->
            @if($appartement->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl shadow-md">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 text-red-600 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-red-600 mb-3">Aucune chambre disponible</h3>
                    <p class="text-gray-600 text-lg max-w-md mx-auto">Cette propriété n'a actuellement aucune chambre disponible. Veuillez vérifier ultérieurement.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Initialisation du slider amélioré -->
@if($propriete->medias->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.swiper-container', {
            loop: true,
            effect: 'fade',
            speed: 800,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>
@endif
@endsection