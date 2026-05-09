@extends('layouts.plaout')

@section('title', 'Nos Appartements Disponibles')

@section('content')
{{-- Flatpickr CDN --}}
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<style>
    .flatpickr-calendar.inline { width: 100% !important; max-width: 100%; box-shadow: none !important; border: none !important; }
    .flatpickr-calendar.inline .flatpickr-innerContainer { max-width: 100%; }
    .flatpickr-calendar.inline .flatpickr-rContainer { width: 100%; }
    .flatpickr-calendar.inline .flatpickr-days { width: 100%; }
    .flatpickr-calendar.inline .dayContainer { width: 100%; max-width: 100%; min-width: 100%; }
    .flatpickr-months { margin-bottom: 8px; }
</style>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

<div class="flex-grow" style="background: linear-gradient(135deg, #f8f9fc 0%, #eef1f8 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Page Header --}}
        <div class="pt-8 pb-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-red-500 mb-1"></p>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight"></h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">
                        <span class="font-bold text-gray-900">{{ $appartement->total() }}</span> appartements trouvés
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="text-xs text-gray-400">Page {{ $appartement->currentPage() }}/{{ $appartement->lastPage() }}</span>
                    {{-- Mobile Filter Button --}}
                    <button onclick="toggleModal()" class="lg:hidden bg-white border border-gray-200 text-gray-700 py-2 px-4 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm hover:shadow transition-all">
                        <i class="fas fa-sliders-h text-red-500"></i> Filtres
                    </button>
                </div>
            </div>

            {{-- Active Filters Pills --}}
            @if(request()->hasAny(['date_arrivee', 'date_depart', 'propriete', 'type', 'capacite', 'prix_max', 'equipements']))
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider mr-1">Filtres :</span>
                @if(request('date_arrivee') && request('date_depart'))
                    <span class="inline-flex items-center bg-red-50 text-red-700 pl-2.5 pr-1.5 py-1 rounded-md text-xs font-medium border border-red-100">
                        <i class="fas fa-calendar-alt mr-1.5 text-red-400 text-[10px]"></i>
                        {{ date('d/m', strtotime(request('date_arrivee'))) }} → {{ date('d/m', strtotime(request('date_depart'))) }}
                        <button onclick="removeFilter('date_arrivee,date_depart')" class="ml-1.5 text-red-300 hover:text-red-600 transition-colors p-0.5">
                            <i class="fas fa-times text-[9px]"></i>
                        </button>
                    </span>
                @endif

                @if(request('prix_max'))
                    <span class="inline-flex items-center bg-amber-50 text-amber-700 pl-2.5 pr-1.5 py-1 rounded-md text-xs font-medium border border-amber-100">
                        <i class="fas fa-coins mr-1.5 text-amber-400 text-[10px]"></i>
                        Max {{ number_format(request('prix_max'), 0, ',', ' ') }} F
                        <button onclick="removeFilter('prix_max')" class="ml-1.5 text-amber-300 hover:text-amber-600 transition-colors p-0.5">
                            <i class="fas fa-times text-[9px]"></i>
                        </button>
                    </span>
                @endif
                <a href="{{ route('chambres.index') }}" class="text-xs text-gray-400 hover:text-red-500 font-medium ml-1 transition-colors">
                    Tout effacer ×
                </a>
            </div>
            @endif
        </div>

        {{-- Main Layout --}}
        <div class="flex flex-col lg:flex-row gap-6 pb-12">
            
            {{-- ══════════════════════════════════════════ --}}
            {{-- FILTERS SIDEBAR (Desktop) --}}
            {{-- ══════════════════════════════════════════ --}}
            <div class="hidden lg:block lg:w-[260px] flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100/80 sticky top-20 overflow-hidden">
                    {{-- Filter Header --}}
                    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                            <span class="w-6 h-6 bg-red-600 rounded-md flex items-center justify-center">
                                <i class="fas fa-filter text-white text-[9px]"></i>
                            </span>
                            Filtres
                        </h2>
                    </div>

                    <form method="GET" action="{{ route('chambres.index') }}" class="p-5 space-y-5" id="searchForm">
                        {{-- Stay Dates (clickable card that opens calendar sidebar) --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Dates de séjour</label>
                            <input type="hidden" id="date_arrivee" name="date_arrivee" value="{{ request('date_arrivee') }}">
                            <input type="hidden" id="date_depart" name="date_depart" value="{{ request('date_depart') }}">
                            <button type="button" id="openCalendarSidebar" class="w-full bg-gray-50/80 border border-gray-200 rounded-lg p-3 text-left hover:border-red-300 hover:bg-red-50/30 transition-all group cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-red-50 rounded-md flex items-center justify-center group-hover:bg-red-100 transition-colors">
                                            <i class="fas fa-calendar-alt text-red-500 text-[10px]"></i>
                                        </div>
                                        <div>
                                            <span id="filterDateDisplay" class="block text-xs font-bold text-gray-700">
                                                @if(request('date_arrivee') && request('date_depart'))
                                                    {{ date('d/m', strtotime(request('date_arrivee'))) }} → {{ date('d/m', strtotime(request('date_depart'))) }}
                                                @else
                                                    Choisir les dates
                                                @endif
                                            </span>
                                            <span class="text-[9px] text-gray-400">Arrivée / Départ</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-300 text-[9px] group-hover:text-red-400 transition-colors"></i>
                                </div>
                            </button>
                        </div>


                        {{-- Room Type --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Type d'appartement</label>
                            <select id="type" name="type" class="w-full text-xs h-9 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 focus:border-red-400 bg-gray-50/50 px-3 transition-all">
                                <option value="">Tous</option>
                                @foreach($typesappartement as $type)
                                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Capacity --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Personnes</label>
                            <select id="capacite" name="capacite" class="w-full text-xs h-9 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 focus:border-red-400 bg-gray-50/50 px-3 transition-all">
                                <option value="">Toutes</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ request('capacite') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i > 1 ? 'pers.' : 'pers.' }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        {{-- Maximum Budget --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">
                                Budget max: <span id="prixValue" class="text-red-600 font-black">{{ request('prix_max') ? number_format(request('prix_max'), 0, ',', ' ') : '50 000' }}</span> F
                            </label>
                            <input type="range" id="prix_max" name="prix_max"
                                   min="10000" max="200000" step="5000"
                                   value="{{ request('prix_max') ? request('prix_max') : 50000 }}"
                                   class="w-full accent-red-600"
                                   oninput="document.getElementById('prixValue').innerText = new Intl.NumberFormat('fr-FR').format(this.value)">
                        </div>

                        {{-- Amenities --}}
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Équipements</label>
                            <div class="max-h-36 overflow-y-auto border border-gray-100 rounded-lg p-1.5 bg-gray-50/50 space-y-0.5">
                                @foreach($equipements as $equipement)
                                    <label class="flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-white rounded-md cursor-pointer transition-colors">
                                        <input type="checkbox" name="equipements[]" value="{{ $equipement->id }}"
                                               {{ in_array($equipement->id, request('equipements', [])) ? 'checked' : '' }}
                                               class="h-3.5 w-3.5 text-red-600 border-gray-300 rounded accent-red-600">
                                        <span>{{ $equipement->nom }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-2 pt-1">
                            <button type="submit" class="w-full bg-gray-900 text-white py-2.5 rounded-lg hover:bg-red-600 text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 transition-colors shadow-sm">
                                <i class="fas fa-search text-[10px]"></i>
                                Appliquer
                            </button>
                            <a href="{{ route('chambres.index') }}" class="block w-full bg-gray-50 text-gray-500 py-2 rounded-lg hover:bg-gray-100 text-xs text-center font-medium transition-colors">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- ROOMS GRID --}}
            {{-- ══════════════════════════════════════════ --}}
            <div class="flex-1 min-w-0">
                <div class="space-y-4">
                    @forelse($appartement as $chambre)
                    <div class="group bg-white rounded-xl border border-gray-100/80 shadow-sm hover:shadow-lg hover:border-red-100 transition-all duration-300 overflow-hidden">
                        <div class="flex flex-col sm:flex-row">
                            {{-- Image Section --}}
                            <div class="w-full sm:w-72 flex-shrink-0 p-2.5">
                                <div class="relative rounded-lg overflow-hidden" style="aspect-ratio: 4/3;">
                                    @if($chambre->medias->first())
                                        <img src="{{ Storage::url($chambre->medias->first()->chemin_fichier) }}"
                                             alt="{{ $chambre->typeChambre->nom }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                             loading="lazy">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-red-50 via-gray-50 to-red-50 flex flex-col items-center justify-center">
                                            <i class="fas fa-bed text-red-200 text-3xl mb-2"></i>
                                            <span class="text-[10px] text-gray-400 font-medium">Photo à venir</span>
                                        </div>
                                    @endif

                                    {{-- Price Badge --}}
                                    <div class="absolute top-2.5 left-2.5">
                                        <div class="bg-white/95 backdrop-blur-sm px-2.5 py-1 rounded-md shadow-sm border border-white/50">
                                            <span class="font-black text-red-600 text-sm">{{ number_format($chambre->prix_base, 0, ',', ' ') }} F</span>
                                            <span class="text-gray-400 text-[10px] font-medium">/nuit</span>
                                        </div>
                                    </div>

                                    {{-- Rating Badge --}}
                                    @if($chambre->note_moyenne > 0)
                                    <div class="absolute top-2.5 right-2.5">
                                        <div class="bg-amber-400 text-amber-900 px-2 py-0.5 rounded-md text-[10px] font-bold flex items-center gap-0.5 shadow-sm">
                                            <i class="fas fa-star text-[8px]"></i>
                                            {{ number_format($chambre->note_moyenne, 1) }}
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Image Counter --}}
                                    @if($chambre->medias->count() > 1)
                                    <div class="absolute bottom-2.5 right-2.5">
                                        <button class="image-trigger bg-black/50 backdrop-blur-sm text-white px-2 py-1 rounded-md text-[10px] font-medium flex items-center gap-1 hover:bg-black/70 transition-colors cursor-pointer"
                                                data-chambre-id="{{ $chambre->id }}"
                                                data-image-index="0">
                                            <i class="fas fa-images text-[9px]"></i>
                                            {{ $chambre->medias->count() }}
                                        </button>
                                    </div>
                                    @endif
                                </div>

                                {{-- Secondary Images Row --}}
                                @if($chambre->medias->count() > 1)
                                <div class="flex gap-1.5 mt-1.5">
                                    @php
                                        $secondaryImages = $chambre->medias->slice(1);
                                        $displayCount = min(3, $secondaryImages->count());
                                    @endphp
                                    @for($i = 0; $i < $displayCount; $i++)
                                        <div class="flex-1 relative rounded-md overflow-hidden cursor-pointer image-trigger group/thumb"
                                             data-chambre-id="{{ $chambre->id }}"
                                             data-image-index="{{ $i + 1 }}"
                                             style="aspect-ratio: 16/9;">
                                            <img src="{{ Storage::url($secondaryImages->skip($i)->first()->chemin_fichier) }}"
                                                 alt="Image {{ $i + 2 }}"
                                                 class="w-full h-full object-cover group-hover/thumb:scale-110 transition-transform duration-300"
                                                 loading="lazy">
                                            @if($i == 2 && $secondaryImages->count() > 3)
                                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center rounded-md">
                                                    <span class="text-white text-[10px] font-bold">+{{ $secondaryImages->count() - 3 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                                @endif
                            </div>

                            {{-- Details Section --}}
                            <div class="flex-1 p-5 flex flex-col justify-between min-w-0">
                                <div>
                                    {{-- Title & Location --}}
                                    <div class="mb-3">
                                        <h3 class="text-base font-black text-gray-900 tracking-tight mb-0.5 group-hover:text-red-600 transition-colors">{{ $chambre->typeChambre->nom }}</h3>
                                        <p class="text-xs text-gray-400 flex items-center gap-1.5">
                                            <i class="fas fa-map-marker-alt text-red-400 text-[9px]"></i>
                                            Ch. {{ $chambre->numero_chambre }}
                                        </p>
                                    </div>

                                    {{-- Description --}}
                                    <p class="text-xs text-gray-500 leading-relaxed mb-4 line-clamp-2">
                                        {{ $chambre->typeChambre->description ? Str::limit($chambre->typeChambre->description, 100) : 'Parfait pour les séjours de courte durée.' }}
                                    </p>

                                    {{-- Room Specs --}}
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="flex items-center gap-1.5 text-xs text-gray-600 bg-gray-50 px-2.5 py-1.5 rounded-md">
                                            <i class="fas fa-users text-red-400 text-[10px]"></i>
                                            <span class="font-medium">{{ $chambre->typeChambre->capacite_standard }}-{{ $chambre->typeChambre->capacite_max }} pers</span>
                                        </div>
                                        @if($chambre->typeChambre->superficie)
                                        <div class="flex items-center gap-1.5 text-xs text-gray-600 bg-gray-50 px-2.5 py-1.5 rounded-md">
                                            <i class="fas fa-expand text-emerald-400 text-[10px]"></i>
                                            <span class="font-medium">{{ $chambre->typeChambre->superficie }}m²</span>
                                        </div>
                                        @endif
                                        @if($chambre->typeChambre->lit)
                                        <div class="flex items-center gap-1.5 text-xs text-gray-600 bg-gray-50 px-2.5 py-1.5 rounded-md">
                                            <i class="fas fa-bed text-purple-400 text-[10px]"></i>
                                            <span class="font-medium">{{ Str::limit($chambre->typeChambre->lit, 12) }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Amenities Tags --}}
                                    @if($chambre->equipements->count() > 0)
                                    <div class="flex flex-wrap gap-1.5 mb-4">
                                        @foreach($chambre->equipements->take(3) as $equipement)
                                            <span class="text-[10px] font-medium text-gray-500 bg-gray-50 border border-gray-100 px-2 py-1 rounded-md">
                                                {{ $equipement->nom }}
                                            </span>
                                        @endforeach
                                        @if($chambre->equipements->count() > 3)
                                            <span class="text-[10px] font-medium text-red-500 bg-red-50 border border-red-100 px-2 py-1 rounded-md">
                                                +{{ $chambre->equipements->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                {{-- Footer Actions --}}
                                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                    <div class="flex items-center gap-1.5">
                                        @auth
                                            <button class="w-8 h-8 bg-gray-50 hover:bg-red-50 rounded-lg flex items-center justify-center transition-colors favorite-btn"
                                                    data-chambre-id="{{ $chambre->id }}"
                                                    data-is-favorited="{{ Auth::user()->favoris()->where('chambre_id', $chambre->id)->exists() ? 'true' : 'false' }}"
                                                    aria-label="Ajouter aux favoris">
                                                <i class="fas fa-heart text-xs {{ Auth::user()->favoris()->where('chambre_id', $chambre->id)->exists() ? 'text-red-400' : 'text-gray-300' }} hover:text-red-400 transition-colors"></i>
                                            </button>
                                        @endauth
                                        @guest
                                            <button type="button" class="w-8 h-8 bg-gray-50 hover:bg-red-50 rounded-lg flex items-center justify-center transition-colors"
                                                    onclick="showLoginModal()"
                                                    aria-label="Ajouter aux favoris">
                                                <i class="fas fa-heart text-xs text-gray-300 hover:text-red-400 transition-colors"></i>
                                            </button>
                                        @endguest
                                        <button class="w-8 h-8 bg-gray-50 hover:bg-red-50 rounded-lg flex items-center justify-center transition-colors share-btn"
                                                data-chambre-id="{{ $chambre->id }}"
                                                data-url="{{ route('chambres.show', $chambre->id) }}"
                                                data-title="{{ $chambre->typeChambre->nom }}"
                                                aria-label="Partager">
                                            <i class="fas fa-share-alt text-xs text-gray-300 hover:text-red-400 transition-colors"></i>
                                        </button>
                                    </div>
                                    <a href="{{ route('chambres.show', $chambre->id) }}"
                                       class="inline-flex items-center gap-2 bg-gray-900 hover:bg-red-600 text-white px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all duration-200 shadow-sm hover:shadow-md group/btn">
                                        <span>Disponibilité</span>
                                        <i class="fas fa-arrow-right text-[10px] group-hover/btn:translate-x-0.5 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Image Modal --}}
                    <div id="imageModal{{ $chambre->id }}" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm">
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <button class="close-modal absolute top-4 right-4 z-60 text-white/60 hover:text-white transition-colors bg-white/10 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"
                                    data-chambre-id="{{ $chambre->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            <button class="prev-image absolute left-4 top-1/2 transform -translate-y-1/2 z-60 text-white/60 hover:text-white transition-colors bg-white/10 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"
                                    data-chambre-id="{{ $chambre->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button class="next-image absolute right-4 top-1/2 transform -translate-y-1/2 z-60 text-white/60 hover:text-white transition-colors bg-white/10 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"
                                    data-chambre-id="{{ $chambre->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div class="relative max-w-5xl max-h-full">
                                <img id="modalImage{{ $chambre->id }}"
                                     src=""
                                     alt="Image agrandie"
                                     class="max-w-full max-h-[85vh] object-contain rounded-xl">
                                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-medium">
                                    <span id="currentImageIndex{{ $chambre->id }}">1</span> / <span id="totalImages{{ $chambre->id }}">{{ $chambre->medias->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Image Data --}}
                    <script type="application/json" id="imageData{{ $chambre->id }}">
                        {
                            "chambreId": {{ $chambre->id }},
                            "images": [
                                @foreach($chambre->medias as $index => $media)
                                    {
                                        "url": "{{ Storage::url($media->chemin_fichier) }}",
                                        "alt": "Image {{ $index + 1 }}"
                                    }@if(!$loop->last),@endif
                                @endforeach
                            ]
                        }
                    </script>

                    @empty
                    {{-- Empty State --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-16 text-center">
                        <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <i class="fas fa-search text-red-300 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 mb-2 tracking-tight">Aucun appartement trouvé</h3>
                        <p class="text-sm text-gray-400 mb-6 max-w-sm mx-auto">Nous n'avons pas trouvé d'appartement correspondant à vos critères actuels. Essayez d'élargir votre recherche.</p>
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('chambres.index') }}"
                               class="bg-gray-900 hover:bg-red-600 text-white px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                Réinitialiser
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($appartement->hasPages())
                <div class="mt-8">
                    <div class="bg-white rounded-xl border border-gray-100 p-4 flex justify-center shadow-sm">
                        {{ $appartement->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- MOBILE FILTERS MODAL --}}
{{-- ══════════════════════════════════════════ --}}
<div id="filtersModal" x-data="{ open: false }" x-show="open" @click.away="open = false" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/40 backdrop-blur-sm" x-cloak>
    <div class="bg-white rounded-t-2xl sm:rounded-xl p-6 w-full sm:max-w-md max-h-[85vh] overflow-y-auto shadow-2xl">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-sm font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                <span class="w-6 h-6 bg-red-500 rounded-md flex items-center justify-center">
                    <i class="fas fa-filter text-white text-[9px]"></i>
                </span>
                Filtres
            </h2>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <form method="GET" action="{{ route('chambres.index') }}" class="space-y-5" id="searchFormModal">
            {{-- Stay Dates --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Dates de séjour</label>
                <div class="space-y-2">
                    <input type="date" id="date_arrivee_modal" name="date_arrivee"
                           value="{{ request('date_arrivee') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full text-xs h-9 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 px-3">
                    <input type="date" id="date_depart_modal" name="date_depart"
                           value="{{ request('date_depart') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full text-xs h-9 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 px-3">
                </div>
            </div>


            {{-- Room Type --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Type d'appartement</label>
                <select id="type_modal" name="type" class="w-full text-xs h-9 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 px-3">
                    <option value="">Tous</option>
                    @foreach($typesappartement as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Capacity --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Personnes</label>
                <select id="capacite_modal" name="capacite" class="w-full text-xs h-9 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-200 px-3">
                    <option value="">Toutes</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ request('capacite') == $i ? 'selected' : '' }}>
                            {{ $i }} pers.
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Maximum Budget --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Budget max: <span id="prixValue_modal" class="text-red-600 font-black">{{ request('prix_max') ? number_format(request('prix_max'), 0, ',', ' ') : '50 000' }}</span> F
                </label>
                <input type="range" id="prix_max_modal" name="prix_max"
                       min="10000" max="200000" step="5000"
                       value="{{ request('prix_max') ? request('prix_max') : 50000 }}"
                       class="w-full accent-red-500"
                       oninput="document.getElementById('prixValue_modal').innerText = new Intl.NumberFormat('fr-FR').format(this.value)">
            </div>

            {{-- Amenities --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Équipements</label>
                <div class="max-h-36 overflow-y-auto border border-gray-100 rounded-lg p-1.5 bg-gray-50/50 space-y-0.5">
                    @foreach($equipements as $equipement)
                        <label class="flex items-center gap-2 px-2 py-1.5 text-xs text-gray-700 hover:bg-white rounded-md cursor-pointer transition-colors">
                            <input type="checkbox" name="equipements[]" value="{{ $equipement->id }}"
                                   {{ in_array($equipement->id, request('equipements', [])) ? 'checked' : '' }}
                                   class="h-3.5 w-3.5 text-red-500 border-gray-300 rounded accent-red-500">
                            <span>{{ $equipement->nom }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-2 pt-1">
                <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg hover:bg-red-600 text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 transition-colors shadow-sm">
                    <i class="fas fa-search text-[10px]"></i>
                    Appliquer les filtres
                </button>
                <a href="{{ route('chambres.index') }}" class="block w-full bg-gray-50 text-gray-500 py-2.5 rounded-lg hover:bg-gray-100 text-xs text-center font-medium transition-colors">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- LOGIN MODAL (Favorites) --}}
{{-- ══════════════════════════════════════════ --}}
<div id="loginModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-2xl p-6 w-full mx-4 max-w-sm shadow-xl transform scale-95 transition-transform duration-300" id="loginModalContent">
        <div class="text-center">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-lock text-red-500 text-xl"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 tracking-tight mb-2">Connexion requise</h3>
            <p class="text-xs text-gray-500 mb-6 flex-wrap leading-relaxed">Veuillez vous connecter pour ajouter cet appartement à vos favoris.</p>
            <div class="flex gap-3">
                <button onclick="closeLoginModal()" class="flex-1 px-4 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors border border-gray-200">
                    Annuler
                </button>
                <a href="{{ route('login') }}" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-colors inline-block text-center shadow-sm">
                    Se connecter
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════ --}}
{{-- CALENDAR SIDEBAR DRAWER --}}
{{-- ══════════════════════════════════════════ --}}
<div id="calendarOverlay" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[100] transition-opacity duration-300 opacity-0"></div>

<div id="calendarSidebar" class="fixed top-0 right-0 h-screen w-full md:w-[400px] bg-white shadow-[-10px_0_40px_rgba(0,0,0,0.08)] z-[101] transform translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] flex flex-col border-l border-gray-200">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10 flex-shrink-0">
        <div>
            <h3 class="text-base font-black text-gray-900 uppercase tracking-tighter">Dates du Séjour</h3>
            <p class="text-[9px] uppercase font-bold text-gray-400 tracking-[0.2em]">Sélectionnez votre période</p>
        </div>
        <button type="button" id="closeCalendarSidebar" class="text-gray-400 hover:text-gray-900 transition-colors bg-gray-50 hover:bg-gray-100 p-2.5 rounded-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50/30">
        <div class="w-full mb-4 flex justify-center rounded-xl bg-white p-3 border border-gray-100 shadow-sm" style="min-height: 280px;">
            <div id="filter_calendar_container" class="w-full max-w-full overflow-hidden flex justify-center">
                <input type="text" id="filter_date_range_picker" style="display:none;">
            </div>
        </div>
        
        <div class="flex items-center justify-between bg-white shadow-sm border border-gray-100 p-3.5 rounded-xl">
            <div class="w-1/2">
                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em] mb-1">Check-in</span>
                <span id="filter_display_arr" class="font-bold text-gray-900 text-sm">{{ request('date_arrivee') ? date('d/m/Y', strtotime(request('date_arrivee'))) : '-' }}</span>
            </div>
            <div class="h-6 w-px bg-gray-200 mx-3"></div>
            <div class="w-1/2 text-right">
                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em] mb-1">Check-out</span>
                <span id="filter_display_dep" class="font-bold text-gray-900 text-sm">{{ request('date_depart') ? date('d/m/Y', strtotime(request('date_depart'))) : '-' }}</span>
            </div>
        </div>
    </div>
    
    <div class="p-5 border-t border-gray-100 bg-white shadow-[0_-5px_15px_rgba(0,0,0,0.02)] flex-shrink-0 z-10">
        <button type="button" id="applyCalendarDates" class="w-full bg-gray-900 text-white py-3.5 rounded-xl text-xs font-black uppercase tracking-[0.1em] shadow-lg hover:bg-red-600 hover:shadow-red-500/30 transition-all transform active:scale-95">Valider les dates</button>
    </div>
</div>

@endsection

@section('scripts')
{{-- ══════════════════════════════════════════ --}}
{{-- SCRIPTS --}}
{{-- ══════════════════════════════════════════ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Object pour stocker les données de chaque chambre
        window.chambreModals = window.chambreModals || {};

        document.querySelectorAll('[id^="imageData"]').forEach(dataScript => {
            const chambreId = dataScript.id.replace('imageData', '');
            if (document.getElementById('imageModal' + chambreId)) {
                initChambre(chambreId);
            }
        });
        
        function initChambre(chambreId) {
            const dataScript = document.getElementById('imageData' + chambreId);
            if (!dataScript) return;
            
            try {
                const data = JSON.parse(dataScript.textContent);
                window.chambreModals[chambreId] = {
                    images: data.images,
                    currentIndex: 0
                };
            } catch (e) {
                console.error(`Erreur parsing JSON chambre ${chambreId}:`, e);
            }
        }
        
        function openModal(chambreId, imageIndex) {
            const modal = document.getElementById('imageModal' + chambreId);
            if (!modal) return;
            
            if (!window.chambreModals[chambreId]) {
                initChambre(chambreId);
            }
            
            const modalData = window.chambreModals[chambreId];
            if (!modalData || !modalData.images.length) return;
            
            const validIndex = Math.max(0, Math.min(imageIndex, modalData.images.length - 1));
            modalData.currentIndex = validIndex;
            
            updateModalImage(chambreId);
            modal.classList.remove('hidden');
            
            requestAnimationFrame(() => {
                modal.style.opacity = '1';
            });
            
            document.body.style.overflow = 'hidden';
            modal.focus();
        }

        function closeModal(chambreId) {
            document.getElementById('imageModal' + chambreId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        function nextImage(chambreId) {
            const modal = window.chambreModals[chambreId];
            modal.currentIndex = (modal.currentIndex + 1) % modal.images.length;
            updateModalImage(chambreId);
        }
        
        function previousImage(chambreId) {
            const modal = window.chambreModals[chambreId];
            modal.currentIndex = modal.currentIndex === 0 ? modal.images.length - 1 : modal.currentIndex - 1;
            updateModalImage(chambreId);
        }
        
        function updateModalImage(chambreId) {
            const modal = window.chambreModals[chambreId];
            const modalImage = document.getElementById('modalImage' + chambreId);
            const currentIndexSpan = document.getElementById('currentImageIndex' + chambreId);
            
            if (modalImage && currentIndexSpan && modal.images[modal.currentIndex]) {
                modalImage.src = modal.images[modal.currentIndex].url;
                modalImage.alt = modal.images[modal.currentIndex].alt;
                currentIndexSpan.textContent = modal.currentIndex + 1;
            }
        }
        
        // Event delegation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.image-trigger')) {
                const trigger = e.target.closest('.image-trigger');
                const chambreId = parseInt(trigger.dataset.chambreId);
                const imageIndex = parseInt(trigger.dataset.imageIndex);
                openModal(chambreId, imageIndex);
            }
            
            if (e.target.closest('.close-modal')) {
                const button = e.target.closest('.close-modal');
                const chambreId = parseInt(button.dataset.chambreId);
                closeModal(chambreId);
            }
            
            if (e.target.closest('.next-image')) {
                const button = e.target.closest('.next-image');
                const chambreId = parseInt(button.dataset.chambreId);
                nextImage(chambreId);
            }
            
            if (e.target.closest('.prev-image')) {
                const button = e.target.closest('.prev-image');
                const chambreId = parseInt(button.dataset.chambreId);
                previousImage(chambreId);
            }
        });
        
        // Close on background click
        document.addEventListener('click', function(e) {
            if (e.target.id && e.target.id.startsWith('imageModal')) {
                const chambreId = parseInt(e.target.id.replace('imageModal', ''));
                closeModal(chambreId);
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const openModalEl = document.querySelector('[id^="imageModal"]:not(.hidden)');
            if (openModalEl) {
                const chambreId = parseInt(openModalEl.id.replace('imageModal', ''));
                switch(e.key) {
                    case 'Escape': closeModal(chambreId); break;
                    case 'ArrowLeft': previousImage(chambreId); break;
                    case 'ArrowRight': nextImage(chambreId); break;
                }
            }
        });
    });

    // Toggle Modal
    function toggleModal() {
        function waitForAlpine(callback) {
            if (window.Alpine && window.Alpine.version) {
                callback();
            } else {
                setTimeout(() => waitForAlpine(callback), 50);
            }
        }
        
        waitForAlpine(() => {
            const modal = document.getElementById('filtersModal');
            if (modal && modal._x_dataStack) {
                const data = modal._x_dataStack[0];
                if (data.hasOwnProperty('open')) {
                    data.open = !data.open;
                }
            }
        });
    }

    // Date Validation
    document.addEventListener('DOMContentLoaded', function() {
        const dateArrivee = document.getElementById('date_arrivee');
        const dateDepart = document.getElementById('date_depart');
        const dateArriveeModal = document.getElementById('date_arrivee_modal');
        const dateDepartModal = document.getElementById('date_depart_modal');
        const searchForm = document.getElementById('searchForm');
        const searchFormModal = document.getElementById('searchFormModal');

        function validateDates(arriveeInput, departInput) {
            if (arriveeInput.value && departInput.value) {
                const arrivee = new Date(arriveeInput.value);
                const depart = new Date(departInput.value);
                if (depart <= arrivee) {
                    departInput.setCustomValidity('La date de départ doit être après la date d\'arrivée');
                } else {
                    departInput.setCustomValidity('');
                }
            }
        }

        // Desktop Form
        if (dateArrivee && dateDepart) {
            dateArrivee.addEventListener('change', function() {
                if (this.value) {
                    const nextDay = new Date(this.value);
                    nextDay.setDate(nextDay.getDate() + 1);
                    dateDepart.min = nextDay.toISOString().split('T')[0];
                    if (dateDepart.value && new Date(dateDepart.value) <= new Date(this.value)) {
                        dateDepart.value = nextDay.toISOString().split('T')[0];
                    }
                }
                validateDates(dateArrivee, dateDepart);
            });

            dateDepart.addEventListener('change', () => validateDates(dateArrivee, dateDepart));

            searchForm.addEventListener('submit', function(e) {
                validateDates(dateArrivee, dateDepart);
                if (!dateDepart.checkValidity()) {
                    e.preventDefault();
                    window.toast.warning('Veuillez vérifier vos dates de séjour');
                }
            });
        }

        // Modal Form
        if (dateArriveeModal && dateDepartModal) {
            dateArriveeModal.addEventListener('change', function() {
                if (this.value) {
                    const nextDay = new Date(this.value);
                    nextDay.setDate(nextDay.getDate() + 1);
                    dateDepartModal.min = nextDay.toISOString().split('T')[0];
                    if (dateDepartModal.value && new Date(dateDepartModal.value) <= new Date(this.value)) {
                        dateDepartModal.value = nextDay.toISOString().split('T')[0];
                    }
                }
                validateDates(dateArriveeModal, dateDepartModal);
            });

            dateDepartModal.addEventListener('change', () => validateDates(dateArriveeModal, dateDepartModal));

            searchFormModal.addEventListener('submit', function(e) {
                validateDates(dateArriveeModal, dateDepartModal);
                if (!dateDepartModal.checkValidity()) {
                    e.preventDefault();
                    window.toast.warning('Veuillez vérifier vos dates de séjour');
                }
            });
        }

        // Cookie Management
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        const savedDateArrivee = getCookie('search_date_arrivee');
        const savedDateDepart = getCookie('search_date_depart');
        const savedCapacite = getCookie('search_capacite');
        const savedPropriete = getCookie('search_propriete');
        const savedType = getCookie('search_type');
        const savedPrixMax = getCookie('search_prix_max');

        if (savedDateArrivee) {
            if (dateArrivee && dateArrivee.value === '') dateArrivee.value = savedDateArrivee;
            if (dateArriveeModal && dateArriveeModal.value === '') dateArriveeModal.value = savedDateArrivee;
        }
        if (savedDateDepart) {
            if (dateDepart && dateDepart.value === '') dateDepart.value = savedDateDepart;
            if (dateDepartModal && dateDepartModal.value === '') dateDepartModal.value = savedDateDepart;
        }
        if (savedCapacite) {
            const capacite = document.getElementById('capacite');
            const capaciteModal = document.getElementById('capacite_modal');
            if (capacite && capacite.value === '') capacite.value = savedCapacite;
            if (capaciteModal && capaciteModal.value === '') capaciteModal.value = savedCapacite;
        }
        if (savedType) {
            const type = document.getElementById('type');
            const typeModal = document.getElementById('type_modal');
            if (type && type.value === '') type.value = savedType;
            if (typeModal && typeModal.value === '') typeModal.value = savedType;
        }
        if (savedPrixMax) {
            const prixMax = document.getElementById('prix_max');
            const prixMaxModal = document.getElementById('prix_max_modal');
            const prixValue = document.getElementById('prixValue');
            const prixValueModal = document.getElementById('prixValue_modal');
            if (prixMax && prixMax.value === '') prixMax.value = savedPrixMax;
            if (prixMaxModal && prixMaxModal.value === '') prixMaxModal.value = savedPrixMax;
            if (prixValue) prixValue.innerText = new Intl.NumberFormat('fr-FR').format(savedPrixMax);
            if (prixValueModal) prixValueModal.innerText = new Intl.NumberFormat('fr-FR').format(savedPrixMax);
        }

        [searchForm, searchFormModal].forEach(form => {
            if (form) {
                form.addEventListener('submit', function() {
                    if (dateArrivee?.value || dateArriveeModal?.value) {
                        document.cookie = `search_date_arrivee=${dateArrivee?.value || dateArriveeModal?.value}; path=/; max-age=${60*60*24*30}`;
                    }
                    if (dateDepart?.value || dateDepartModal?.value) {
                        document.cookie = `search_date_depart=${dateDepart?.value || dateDepartModal?.value}; path=/; max-age=${60*60*24*30}`;
                    }
                    if (document.getElementById('capacite')?.value || document.getElementById('capacite_modal')?.value) {
                        document.cookie = `search_capacite=${document.getElementById('capacite')?.value || document.getElementById('capacite_modal')?.value}; path=/; max-age=${60*60*24*30}`;
                    }
                    if (document.getElementById('type')?.value || document.getElementById('type_modal')?.value) {
                        document.cookie = `search_type=${document.getElementById('type')?.value || document.getElementById('type_modal')?.value}; path=/; max-age=${60*60*24*30}`;
                    }
                    if (document.getElementById('prix_max')?.value || document.getElementById('prix_max_modal')?.value) {
                        document.cookie = `search_prix_max=${document.getElementById('prix_max')?.value || document.getElementById('prix_max_modal')?.value}; path=/; max-age=${60*60*24*30}`;
                    }
                });
            }
        });
    });

    // Remove Filter
    function removeFilter(filterName) {
        const forms = [document.getElementById('searchForm'), document.getElementById('searchFormModal')];
        const inputs = filterName.split(',');
        forms.forEach(form => {
            if (form) {
                inputs.forEach(input => {
                    const element = form.querySelector(`[name="${input}"]`);
                    if (element) element.value = '';
                });
                form.submit();
            }
        });
    }

    // Favorites
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.favorite-btn').forEach(button => {
            button.addEventListener('click', function() {
                const chambreId = this.dataset.chambreId;
                const isFavorited = this.dataset.isFavorited === 'true';
                const heartIcon = this.querySelector('i');

                if (!{{ Auth::check() ? 'true' : 'false' }}) {
                    showLoginModal();
                    return;
                }

                fetch('{{ url("/appartement") }}/' + chambreId + '/favoris', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        this.dataset.isFavorited = 'true';
                        heartIcon.classList.remove('text-gray-300');
                        heartIcon.classList.add('text-red-400');
                    } else if (data.status === 'removed') {
                        this.dataset.isFavorited = 'false';
                        heartIcon.classList.remove('text-red-400');
                        heartIcon.classList.add('text-gray-300');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            });
        });
    });

    // Share
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.share-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const url = this.dataset.url;
                const title = this.dataset.title;
                const text = `Découvrez cet appartement : ${title}\n\n${url}`;

                if (navigator.share) {
                    try {
                        await navigator.share({ title: title, text: text });
                    } catch (error) {
                        console.error('Erreur partage:', error);
                    }
                } else {
                    try {
                        await navigator.clipboard.writeText(text);
                        window.toast.success('Lien copié dans le presse-papier !');
                    } catch (error) {
                        window.toast.info('Lien : ' + url);
                    }
                }
            });
        });
    });

    // Login Modal
    function showLoginModal() {
        const modal = document.getElementById('loginModal');
        const content = document.getElementById('loginModalContent');
        if(modal && content) {
            modal.classList.remove('hidden');
            // small delay for transition
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            });
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLoginModal() {
        const modal = document.getElementById('loginModal');
        const content = document.getElementById('loginModalContent');
        if(modal && content) {
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300); // match transition duration
        }
    }

    // Calendar Sidebar Logic
    (function() {
        const calOverlay = document.getElementById('calendarOverlay');
        const calSidebar = document.getElementById('calendarSidebar');
        const openBtn = document.getElementById('openCalendarSidebar');
        const closeBtn = document.getElementById('closeCalendarSidebar');
        const applyBtn = document.getElementById('applyCalendarDates');

        let filterSelectedArr = '{{ request("date_arrivee") }}';
        let filterSelectedDep = '{{ request("date_depart") }}';

        function openCalSidebar() {
            if(!calOverlay || !calSidebar) return;
            calOverlay.classList.remove('hidden');
            void calOverlay.offsetWidth;
            calOverlay.classList.remove('opacity-0');
            calSidebar.classList.remove('translate-x-full');
        }
        function closeCalSidebar() {
            if(!calOverlay || !calSidebar) return;
            calSidebar.classList.add('translate-x-full');
            calOverlay.classList.add('opacity-0');
            setTimeout(() => calOverlay.classList.add('hidden'), 300);
        }

        if(openBtn) openBtn.addEventListener('click', openCalSidebar);
        if(closeBtn) closeBtn.addEventListener('click', closeCalSidebar);
        if(calOverlay) calOverlay.addEventListener('click', closeCalSidebar);

        // Flatpickr init
        document.addEventListener('DOMContentLoaded', function() {
            if(typeof flatpickr !== 'undefined' && document.getElementById('filter_date_range_picker')) {
                flatpickr("#filter_date_range_picker", {
                    mode: "range",
                    inline: true,
                    showMonths: 1,
                    minDate: "today",
                    locale: "fr",
                    dateFormat: "Y-m-d",
                    appendTo: document.getElementById('filter_calendar_container'),
                    defaultDate: (filterSelectedArr && filterSelectedDep) ? [filterSelectedArr, filterSelectedDep] : [],
                    onChange: function(selectedDates) {
                        if(selectedDates.length === 2) {
                            const arr = selectedDates[0];
                            const dep = selectedDates[1];
                            const pad = n => n.toString().padStart(2, '0');
                            filterSelectedArr = `${arr.getFullYear()}-${pad(arr.getMonth()+1)}-${pad(arr.getDate())}`;
                            filterSelectedDep = `${dep.getFullYear()}-${pad(dep.getMonth()+1)}-${pad(dep.getDate())}`;
                            document.getElementById('filter_display_arr').innerText = `${pad(arr.getDate())}/${pad(arr.getMonth()+1)}/${arr.getFullYear()}`;
                            document.getElementById('filter_display_dep').innerText = `${pad(dep.getDate())}/${pad(dep.getMonth()+1)}/${dep.getFullYear()}`;
                        }
                    }
                });
            }
        });

        // Apply dates to form
        if(applyBtn) {
            applyBtn.addEventListener('click', function() {
                if(filterSelectedArr && filterSelectedDep) {
                    const arrInput = document.getElementById('date_arrivee');
                    const depInput = document.getElementById('date_depart');
                    const display = document.getElementById('filterDateDisplay');
                    
                    if(arrInput) arrInput.value = filterSelectedArr;
                    if(depInput) depInput.value = filterSelectedDep;
                    
                    const arrP = filterSelectedArr.split('-');
                    const depP = filterSelectedDep.split('-');
                    if(display) display.innerText = `${arrP[2]}/${arrP[1]} → ${depP[2]}/${depP[1]}`;

                    // Also update modal form hidden inputs if they exist
                    const modalArr = document.getElementById('date_arrivee_modal');
                    const modalDep = document.getElementById('date_depart_modal');
                    if(modalArr) modalArr.value = filterSelectedArr;
                    if(modalDep) modalDep.value = filterSelectedDep;
                }
                closeCalSidebar();
            });
        }
    })();
</script>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    /* Range Slider */
    input[type="range"] {
        -webkit-appearance: none;
        height: 4px;
        background: #e5e7eb;
        border-radius: 2px;
    }
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        background: #ef4444;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(239, 68, 68, 0.3);
    }

    /* Modal Animation */
    [x-show="open"] {
        animation: slideUp 0.3s ease-out;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Custom Scrollbar */
    .overflow-y-auto::-webkit-scrollbar { width: 4px; }
    .overflow-y-auto::-webkit-scrollbar-track { background: transparent; }
    .overflow-y-auto::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }
    .overflow-y-auto::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    /* Line Clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Card hover lift */
    .group:hover {
        transform: translateY(-1px);
    }
</style>
@endpush
@endsection