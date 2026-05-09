@extends('layouts.plaout')

@section('title', 'Nouvelle réservation')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb et titre -->
        <div class="mb-12 mt-8">
        </div>

        <!-- Messages d'alerte -->
        @if(session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Section principale - Formulaire -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Dates de séjour</h2>
                        <p class="mt-1 text-sm text-gray-500">Sélectionnez vos dates d'arrivée et de départ</p>
                    </div>

                    <!-- Périodes disponibles (si affichées) -->
                    @if(isset($periodesDisponibles) && count($periodesDisponibles) > 0)
                        <div class="bg-red-50 px-6 py-4 border-b border-red-100">
                            <h3 class="text-sm font-medium text-red-800 flex items-center">
                                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Périodes disponibles
                            </h3>
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($periodesDisponibles as $periode)
                                    <button type="button" 
                                        class="text-left bg-white p-3 rounded-md border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-colors duration-200 periode-disponible"
                                        data-debut="{{ $periode['debut'] }}" 
                                        data-fin="{{ $periode['fin'] }}">
                                        <div class="font-medium text-red-700 text-sm">
                                            {{ \Carbon\Carbon::parse($periode['debut'])->format('d/m/Y') }}
                                        </div>
                                        <div class="text-gray-600 text-xs">
                                            au {{ \Carbon\Carbon::parse($periode['fin'])->format('d/m/Y') }}
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Formulaire -->
                    <form action="{{ route('reservations.store') }}" method="POST" class="p-6">
                        @csrf
                        <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id ?? '' }}">
                        @if($demandeVisite) 
                            <input type="hidden" name="visite_id" value="{{ $demandeVisite->id }}">
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date d'arrivée -->
                            <div>
                                <label for="date_arrivee" class="block text-sm font-medium text-gray-700 mb-1">Date d'arrivée *</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="date" id="date_arrivee" name="date_arrivee" 
                                        value="{{ old('date_arrivee', $dateArrivee ?? '') }}"
                                        min="{{ now()->format('Y-m-d') }}"
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 @error('date_arrivee') border-red-500 @enderror" 
                                        required>
                                </div>
                                @error('date_arrivee')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date de départ -->
                            <div>
                                <label for="date_depart" class="block text-sm font-medium text-gray-700 mb-1">Date de départ *</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="date" id="date_depart" name="date_depart" 
                                        value="{{ old('date_depart', $dateDepart ?? '') }}"
                                        min="{{ now()->addDay()->format('Y-m-d') }}"
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 @error('date_depart') border-red-500 @enderror" 
                                        required>
                                </div>
                                @error('date_depart')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Demandes spéciales</label>
                            <textarea id="notes" name="notes" rows="3" 
                                class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                                placeholder="Avez-vous des demandes particulières ?">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Résumé des frais -->
                        <div class="mt-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                                Récapitulatif
                            </h3>

                            <div class="mt-4 space-y-3">
                                <!-- Nombre de nuits -->
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">{{ $chambre->typeChambre->nom }}</span>
                                    <span class="font-medium" id="nuits-count">
                                        @if(isset($nbJours))
                                            {{ $nbJours }} nuit{{ $nbJours > 1 ? 's' : '' }}
                                        @else
                                            Sélectionnez vos dates
                                        @endif
                                    </span>
                                </div>

                                <!-- Prix par nuit -->
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Prix par nuit</span>
                                    <span class="font-medium">{{ number_format($chambre->prix_base, 0, ',', ' ') }} FCFA</span>
                                </div>

                                <!-- Sous-total -->
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-gray-700">Sous-total</span>
                                    <span class="font-medium" id="sous-total">
                                        @if(isset($prixBase))
                                            {{ number_format($prixBase, 0, ',', ' ') }} FCFA
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <!-- Réduction -->
                                <div id="reduction-section" class="@if(!isset($reduction) || $reduction['pourcentage'] == 0) hidden @endif">
                                    <div class="flex justify-between text-green-600">
                                        <span class="text-sm">
                                            Réduction (<span id="reduction-pourcentage">
                                                @if(isset($reduction))
                                                    {{ $reduction['pourcentage'] }}%
                                                @endif
                                            </span>)
                                        </span>
                                        <span class="font-medium text-sm" id="reduction-montant">
                                            @if(isset($reduction))
                                                -{{ number_format($reduction['montant'], 0, ',', ' ') }} FCFA
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-xs text-green-600 mt-1" id="reduction-info">
                                        @if(isset($reduction) && $reduction['pourcentage'] > 0)
                                            🎉 Économisez grâce à votre séjour de {{ $nbJours ?? 0 }} jours !
                                        @endif
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="flex justify-between pt-3 mt-2 border-t border-gray-200">
                                    <span class="text-gray-900 font-medium">Total</span>
                                    <span class="text-red-600 font-bold" id="prix_total">
                                        @if(isset($prixTotal))
                                            {{ number_format($prixTotal, 0, ',', ' ') }} FCFA
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                            <button type="submit" name="save_draft" value="1" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 {{ isset($periodesDisponibles) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ isset($periodesDisponibles) ? 'disabled' : '' }}>
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Sauvegarder en brouillon
                            </button>
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 {{ isset($periodesDisponibles) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ isset($periodesDisponibles) ? 'disabled' : '' }}>
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Payer maintenant
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar - Détails de la chambre -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg overflow-hidden sticky top-6">
                    <!-- Image -->
                    <div class="h-48 bg-gray-200 overflow-hidden">
                        @if($chambre->medias->first())
                            <img src="{{ Storage::url($chambre->medias->first()->chemin_fichier) }}" 
                                alt="{{ $chambre->typeChambre->nom }}" 
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Détails -->
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900">{{ $chambre->typeChambre->nom }}</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $chambre->propriete->nom }}</p>

                        <!-- Statut et capacité -->
                        <div class="mt-3 flex items-center space-x-2">
                            @if(isset($periodesDisponibles))
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Indisponible aux dates choisies
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $chambre->statut === 'disponible' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($chambre->statut) }}
                                </span>
                            @endif
                            <span class="text-sm text-gray-600">
                                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ $chambre->capacite }} personne{{ $chambre->capacite > 1 ? 's' : '' }}
                            </span>
                        </div>

                        <!-- Prix -->
                        <div class="mt-4">
                            <span class="text-2xl font-bold text-red-600">{{ number_format($chambre->prix_base, 0, ',', ' ') }} FCFA</span>
                            <span class="text-sm text-gray-500">/ nuit</span>
                        </div>

                        <!-- Adresse -->
                        <div class="mt-4 flex items-start">
                            <svg class="h-5 w-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div class="ml-2">
                                <p class="text-sm text-gray-600">{{ $chambre->propriete->adresse }}</p>
                                <p class="text-sm text-gray-600">{{ $chambre->propriete->ville }}</p>
                            </div>
                        </div>

                        <!-- Équipements -->
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-900">Équipements inclus</h3>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                @foreach($chambre->equipements as $equipement)
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 text-green-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-xs text-gray-600">{{ $equipement->nom }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Réductions -->
                        <div class="mt-6 bg-green-50 border border-green-100 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-green-800 flex items-center">
                                <svg class="h-4 w-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                                Réductions disponibles
                            </h3>
                            <ul class="mt-2 space-y-1 text-xs text-green-700">
                                <li class="flex items-start">
                                    <svg class="h-3 w-3 text-green-500 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>7+ nuits : <strong>5% de réduction</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-3 w-3 text-green-500 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>14+ nuits : <strong>10% de réduction</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-3 w-3 text-green-500 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>30+ nuits : <strong>15% de réduction</strong></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateArrivee = document.getElementById('date_arrivee');
    const dateDepart = document.getElementById('date_depart');
    const nuitsCount = document.getElementById('nuits-count');
    const sousTotal = document.getElementById('sous-total');
    const prixTotal = document.getElementById('prix_total');
    const reductionSection = document.getElementById('reduction-section');
    const reductionPourcentage = document.getElementById('reduction-pourcentage');
    const reductionMontant = document.getElementById('reduction-montant');
    const reductionInfo = document.getElementById('reduction-info');
    const prixParNuit = {{ $chambre->prix_base }};
    
    // Récupération des dates existantes depuis le serveur (similaire aux prix)
    const dateArriveeExistante = @json($dateArrivee ?? null);
    const dateDepartExistante = @json($dateDepart ?? null);
    
    // Pré-remplissage des dates si elles existent
    if (dateArriveeExistante) {
        dateArrivee.value = dateArriveeExistante;
    }
    if (dateDepartExistante) {
        dateDepart.value = dateDepartExistante;
    }
    
    // Seuils de réduction
    const seuilsReduction = {7: 5, 14: 10, 30: 15};
    
    // Fonction pour calculer la réduction
    function calculerReduction(nbJours, prixBase) {
        let pourcentage = 0;
        for (const [seuilJours, tauxReduction] of Object.entries(seuilsReduction)) {
            if (nbJours >= parseInt(seuilJours)) {
                pourcentage = tauxReduction;
            }
        }
        return {
            pourcentage: pourcentage,
            montant: (prixBase * pourcentage) / 100
        };
    }
    
    // Mettre à jour le prix en fonction des dates
    function updatePrix() {
        const submitButtons = document.querySelectorAll('button[type="submit"]');
        
        if (dateArrivee.value && dateDepart.value) {
            const arrivee = new Date(dateArrivee.value);
            const depart = new Date(dateDepart.value);
            
            if (depart <= arrivee) {
                nuitsCount.innerHTML = '<span class="text-red-500">Date invalide</span>';
                sousTotal.textContent = '-';
                prixTotal.textContent = '-';
                reductionSection.classList.add('hidden');
                
                submitButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                });
                return;
            }
            
            const diffDays = Math.ceil((depart - arrivee) / (1000 * 60 * 60 * 24));
            const prixBase = diffDays * prixParNuit;
            const reduction = calculerReduction(diffDays, prixBase);
            const prixFinal = prixBase - reduction.montant;
            
            // Mise à jour de l'interface
            nuitsCount.textContent = `${diffDays} nuit${diffDays > 1 ? 's' : ''}`;
            sousTotal.textContent = `${prixBase.toLocaleString('fr-FR')} FCFA`;
            
            if (reduction.pourcentage > 0) {
                reductionSection.classList.remove('hidden');
                reductionPourcentage.textContent = reduction.pourcentage;
                reductionMontant.textContent = `-${reduction.montant.toLocaleString('fr-FR')} FCFA`;
                reductionInfo.textContent = `🎉 Économisez grâce à votre séjour de ${diffDays} jours !`;
            } else {
                reductionSection.classList.add('hidden');
            }
            
            prixTotal.textContent = `${prixFinal.toLocaleString('fr-FR')} FCFA`;
            
            // Réactiver les boutons puisque la date a été modifiée
            submitButtons.forEach(btn => {
                btn.disabled = false;
                btn.removeAttribute('disabled');
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
            
            // Cacher le message d'alerte s'il existe
            const alertBlock = document.querySelector('.bg-red-50.border-l-4.border-red-400');
            if (alertBlock) alertBlock.style.display = 'none';
            
            // Mettre à jour le badge de statut si la date a changé
            const statusBadge = document.querySelector('.mt-3.flex.items-center.space-x-2 span.bg-red-100.text-red-800');
            if (statusBadge && statusBadge.textContent.includes('Indisponible')) {
                statusBadge.textContent = 'Dates modifiées';
                statusBadge.classList.replace('bg-red-100', 'bg-blue-100');
                statusBadge.classList.replace('text-red-800', 'text-blue-800');
            }
        }
    }
    
    // Gestion des périodes disponibles
    document.querySelectorAll('.periode-disponible').forEach(periode => {
        periode.addEventListener('click', function() {
            const debut = this.dataset.debut;
            const fin = this.dataset.fin;
            dateArrivee.value = debut;
            
            // Date de départ suggérée (3 jours après)
            const dateDebut = new Date(debut);
            const dateSuggestion = new Date(dateDebut);
            dateSuggestion.setDate(dateDebut.getDate() + 3);
            
            dateDepart.value = dateSuggestion > new Date(fin) ? fin : dateSuggestion.toISOString().split('T')[0];
            updatePrix();
            
            // Mise en surbrillance
            document.querySelectorAll('.periode-disponible').forEach(p => {
                p.classList.remove('border-blue-300', 'bg-blue-50', 'ring-2', 'ring-blue-200');
            });
            this.classList.add('border-blue-300', 'bg-blue-50', 'ring-2', 'ring-blue-200');
        });
    });
    
    // Écouteurs d'événements
    dateArrivee.addEventListener('change', function() {
        const nextDay = new Date(this.value);
        nextDay.setDate(nextDay.getDate() + 1);
        dateDepart.min = nextDay.toISOString().split('T')[0];
        
        if (dateDepart.value && new Date(dateDepart.value) <= new Date(this.value)) {
            dateDepart.value = nextDay.toISOString().split('T')[0];
        }
        updatePrix();
    });
    
    dateDepart.addEventListener('change', updatePrix);
    
    // Initialisation - calcul des prix si les dates sont déjà présentes
    updatePrix();
});
</script>
@endsection