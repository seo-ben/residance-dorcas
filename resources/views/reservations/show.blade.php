@extends('layouts.plaout')

@section('title', 'Détails de la réservation ' . $reservation->reference)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <!-- En-tête avec statut de la réservation -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Réservation {{ $reservation->reference }}</h1>
                        <p class="mt-1 text-sm text-gray-500">Créée le {{ Carbon\Carbon::parse($reservation->created_at)->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @php
                            $statut = 'en_attente';
                            $statutClass = 'bg-amber-100 text-amber-800';
                            $statutTexte = 'En attente de paiement';
                            
                            // Vérifier d'abord si la réservation est annulée
                            if ($reservation->statut === 'annulee') {
                                $statut = 'annulee';
                                $statutClass = 'bg-gray-100 text-gray-800';
                                $statutTexte = 'Annulée';
                            } elseif ($reservation->paiements->where('statut', 'valide')->count() > 0) {
                                $statut = 'valide';
                                $statutClass = 'bg-red-600 text-white';
                                $statutTexte = 'Payée';
                            } elseif ($reservation->paiements->where('statut', 'refuse')->count() > 0) {
                                $statut = 'refuse';
                                $statutClass = 'bg-red-50 text-red-700 border border-red-100';
                                $statutTexte = 'Paiement Refusé';
                            }
                        @endphp
                        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statutClass }}">
                            {{ $statutTexte }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerte d'annulation -->
        @if($reservation->statut === 'annule')
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-8 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Réservation annulée
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Cette réservation a été annulée
                                @if($reservation->date_annulation)
                                    le {{ Carbon\Carbon::parse($reservation->date_annulation)->format('d/m/Y à H:i') }}
                                @endif
                            </p>
                            @if($reservation->motif_annulation)
                                <p class="mt-1"><strong>Motif :</strong> {{ $reservation->motif_annulation }}</p>
                            @endif
                            @if($reservation->remboursement_effectue)
                                <p class="mt-1 text-green-700">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Remboursement effectué
                                </p>
                            @elseif($reservation->paiements->where('statut', 'valide')->count() > 0)
                                <p class="mt-1 text-orange-700">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Remboursement en cours de traitement
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Code QR pour les réservations payées et non annulées -->
        @if($estPayee && isset($qrCodeBase64) && $reservation->statut !== 'annule')
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                <div class="p-6 text-center">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Code QR de votre réservation</h2>
                    <p class="text-sm text-gray-600 mb-4">Présentez ce QR code à votre arrivée pour faciliter votre check-in</p>
                    <div class="inline-block p-3 bg-white border-2 border-red-100 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                        <img src="{{ $qrCodeBase64 }}" style="max-width: 50%;" alt="QR Code de réservation" class="mx-auto">
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Détails de la chambre -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8 @if($reservation->statut === 'annule') opacity-75 @endif">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Détails de la chambre</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                @if($reservation->details->first()->chambre->medias->isNotEmpty())
                                    <img src="{{ asset('storage/' . $reservation->details->first()->chambre->medias->first()->chemin_fichier) }}" 
                                         alt="Photo de la chambre" 
                                         class="w-full h-48 object-cover rounded-lg @if($reservation->statut === 'annule') grayscale @endif">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400">Aucune photo disponible</span>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $reservation->details->first()->chambre->typeChambre->nom }}</h3>
                                    <p class="text-gray-600">{{ $reservation->details->first()->chambre->propriete->nom }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Numéro de chambre:</span>
                                        <p class="font-medium text-gray-900">{{ $reservation->details->first()->chambre->numero_chambre }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Étage:</span>
                                        <p class="font-medium text-gray-900">{{ $reservation->details->first()->chambre->etage }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Équipements -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Équipements</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @forelse($reservation->details->first()->chambre->equipements as $equipement)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700"><i class="fas fa-{{ $equipement->icone }} text-red-600 mr-2 "></i> {{ $equipement->nom }}</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 col-span-3">Aucun équipement listé</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Détails de la réservation -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Détails de la réservation</h2>

                        <!-- Alerte conditionnelle -->
                        @if($alerteMessage && $reservation->statut !== 'annule')
                            <div class="bg-amber-50 border-l-4 border-amber-400 text-amber-700 p-4 mb-6 rounded-lg" role="alert">
                                <p class="font-medium">{{ $alerteMessage }}</p>
                            </div>
                        @endif

                        <!-- Barre de progression (seulement pour les réservations non annulées) -->
                        @if($reservation->statut !== 'annule')
                            <div class="mb-6">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Progression du séjour</h3>
                                
                                @if($aujourdHui < Carbon\Carbon::parse($reservation->date_arrivee))
                                    <p class="text-sm text-gray-600">Votre séjour n'a pas encore commencé. Il débutera le {{ Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}.</p>
                                @else
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $progressionPourcentage }}%"></div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <span>{{ $joursEcoules }} jour(s) écoulé(s) sur {{ $totalJours }} ({{ number_format($progressionPourcentage, 0) }}%)</span>
                                        @if($joursRestants > 0)
                                            <span> - {{ $joursRestants }} jour(s) restant(s)</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Dates du séjour</h3>
                                <div class="mt-2 space-y-2">
                                    <p class="text-gray-900 @if($reservation->statut === 'annule') line-through @endif">
                                        Arrivée: {{ Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-gray-900 @if($reservation->statut === 'annule') line-through @endif">
                                        Départ: {{ Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-gray-900">Durée: {{ $totalJours }} nuit(s)</p>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Tarification</h3>
                                <div class="mt-2 space-y-2">
                                    <p class="text-gray-900">Prix de base: {{ number_format($reservation->prix_original, 0, ',', ' ') }} FCFA</p>
                                    @if($reservation->reduction_pourcentage > 0)
                                        <p class="text-green-600">Réduction: {{ $reservation->reduction_pourcentage }}% (-{{ number_format($reservation->reduction_montant, 0, ',', ' ') }} FCFA)</p>
                                    @endif
                                    <p class="font-semibold text-gray-900">Prix total: {{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</p>
                                    @if($reservation->statut === 'annule' && $reservation->frais_annulation > 0)
                                        <p class="text-red-600">Frais d'annulation: {{ number_format($reservation->frais_annulation, 0, ',', ' ') }} FCFA</p>
                                        <p class="text-red-500">Montant remboursé: {{ number_format($reservation->prix_total - $reservation->frais_annulation, 0, ',', ' ') }} FCFA</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec informations de paiement -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden sticky top-8">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Statut du paiement</h2>
                        @php
                            $dernierPaiement = $reservation->paiements->sortByDesc('created_at')->first();
                        @endphp

                        @if($reservation->statut === 'annulee')
                            <!-- Statut d'annulation -->
                            <div class="bg-red-50 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium text-red-800">Réservation annulée</span>
                                </div>
                                <div class="mt-4 text-sm text-red-700">
                                    @if($reservation->date_annulation)
                                        <p>Date d'annulation: {{ Carbon\Carbon::parse($reservation->date_annulation)->format('d/m/Y H:i') }}</p>
                                    @endif
                                    @if($dernierPaiement && $dernierPaiement->statut === 'valide')
                                        <p class="mt-2">Paiement original: {{ number_format($dernierPaiement->montant, 0, ',', ' ') }} FCFA</p>
                                        @if($reservation->frais_annulation > 0)
                                            <p>Frais d'annulation: {{ number_format($reservation->frais_annulation, 0, ',', ' ') }} FCFA</p>
                                            <p class="font-medium">Montant remboursé: {{ number_format($dernierPaiement->montant - $reservation->frais_annulation, 0, ',', ' ') }} FCFA</p>
                                        @else
                                            <p class="font-medium text-green-700">Remboursement intégral</p>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            @if($reservation->remboursement_effectue)
                                <div class="bg-green-50 rounded-lg p-4 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium text-green-800">Remboursement effectué</span>
                                    </div>
                                    @if($reservation->date_remboursement)
                                        <p class="mt-2 text-sm text-green-700">
                                            Date: {{ Carbon\Carbon::parse($reservation->date_remboursement)->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            @elseif($dernierPaiement && $dernierPaiement->statut === 'valide')
                                <div class="bg-amber-50 rounded-lg p-4 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium text-amber-800">Remboursement en cours</span>
                                    </div>
                                    <p class="mt-2 text-sm text-amber-700">
                                        Le remboursement sera traité dans les 3-5 jours ouvrables.
                                    </p>
                                </div>
                            @endif

                        @elseif($dernierPaiement && $dernierPaiement->statut === 'valide')
                            <div class="bg-green-50 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium text-green-800">Paiement confirmé</span>
                                </div>
                                <div class="mt-4 text-sm text-green-700">
                                    <p>Date: {{ Carbon\Carbon::parse($dernierPaiement->date_paiement)->format('d/m/Y H:i') }}</p>
                                    <p>Méthode: {{ ucfirst($dernierPaiement->methode_paiement) }}</p>
                                    <p>Référence: {{ $dernierPaiement->reference_transaction }}</p>
                                    <p>Montant payé: {{ number_format($dernierPaiement->montant, 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>

                            @if($reservation->code_acces_chambre)
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Code d'accès à votre chambre</h3>
                                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                                        <span class="text-2xl font-mono font-bold text-gray-900">{{ $reservation->code_acces_chambre }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">Ce code vous sera demandé à votre arrivée</p>
                                </div>
                            @endif

                        @elseif($dernierPaiement && $dernierPaiement->statut === 'refuse')
                            <div class="bg-red-50 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium text-red-800">Échec du dernier paiement</span>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-red-700">Le dernier paiement a échoué. Veuillez réessayer.</p>
                                    <a href="{{ route('reservations.payment', $reservation->id) }}" 
                                       class="mt-4 w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Réessayer le paiement
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-amber-50 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium text-amber-800">Paiement en attente</span>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-amber-700">Votre réservation n'est pas encore confirmée. Veuillez procéder au paiement pour la confirmer.</p>
                                    <a href="{{ route('reservations.payment', $reservation->id) }}" 
                                       class="mt-4 w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Procéder au paiement
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Informations de contact -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact</h3>
                            <div class="space-y-3 text-sm">
                                <p class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $reservation->client->user->name }}
                                </p>
                                <p class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $reservation->client->user->email }}
                                </p>
                                @if($reservation->client->user->phone)
                                <p class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $reservation->client->user->phone }}
                                </p>
                                @endif
                            </div>
                        </div>

                        @if($reservation->notes_client)
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                            <p class="text-gray-600 text-sm">{{ $reservation->notes_client }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
