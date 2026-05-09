@extends('layouts.plaout')
@section('title', 'Mes réservations')
@section('content')

<!-- Header simplifié avec gradient moderne -->
<div class="bg-gradient-to-br from-slate-50 to-red-50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center pt-12">
            <h1 class="text-3xl font-bold text-slate-900 mb-3">Mes demandes de visite</h1>
            <p class="text-slate-600 text-lg">Gérez vos demandes de visite et lancer la réservations</p>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-6 py-12">
    <!-- Navigation par onglets moderne -->
    <div class="mb-12" x-data="{ activeTab: 'visites' }">
        <!-- Contenu des onglets -->
        <div x-show="activeTab === 'visites'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            @if($demandes->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-lg font-semibold text-slate-900">Vos demandes de visite</h3>
                        <p class="text-sm text-slate-600 mt-1">{{ $demandes->count() }} demande(s) au total</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Chambre</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date demande</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($demandes as $demande)
                                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                                        
                                        <td class="px-6 py-4">
                                            <span class="text-slate-700">{{ $demande->chambre->typeChambre->nom }}(C-{{ $demande->chambre->numero_chambre }})</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-slate-600">
                                                {{ is_string($demande->date_demande) ? 
                                                   \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') : 
                                                   $demande->date_demande->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($demande->statut == 'en_attente')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200">
                                                    <div class="w-1.5 h-1.5 bg-amber-400 rounded-full mr-2"></div>
                                                    En attente
                                                </span>
                                            @elseif($demande->statut == 'confirmee')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-2"></div>
                                                    Confirmée
                                                </span>
                                            @elseif($demande->statut == 'annulee')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                                    <div class="w-1.5 h-1.5 bg-red-400 rounded-full mr-2"></div>
                                                    Annulée
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 flex">
                                            <a href="{{ route('client.visites.show', $demande->id) }}" 
                                            class="inline-flex items-center gap-2 mt-2  mx-2 px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                détails
                                            </a>
                                            
                                            @if($demande->statut == 'confirmee')
                                                <a href="{{ route('reservations.create', ['chambre_id' => $demande->chambre->id, 'visite_id' => $demande->id]) }}" 
                                                    class="inline-flex items-center gap-2 px-4 py-2 mt-2 text-sm font-medium text-white bg-green-600 border border-green-700 rounded-lg hover:bg-green-700 hover:border-green-800 transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 003 3z" />
                                                        </svg>
                                                        Réserver
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Aucune demande de visite</h3>
                    <p class="text-slate-600 mb-8 max-w-md mx-auto">Vous n'avez pas encore effectué de demande de visite pour nos hébergements.</p>
                    <a href="{{ route('chambres.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Découvrir nos appartement
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Animations fluides pour les transitions */
    [x-cloak] { display: none !important; }
    
    /* Amélioration des hover states */
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
    
    /* Effet de focus moderne */
    .focus\:ring-4:focus {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(4px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }
</style>

@endsection