@extends('layouts.plaout')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg mt-10">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-gray-800 mb-6">Mes réservations</h1>
                
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                
                @if($reservations->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune réservation</h3>
                        <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore effectué de réservation.</p>
                        <div class="mt-6">
                            <a href="{{ route('chambres.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                </svg>
                                Découvrir nos appartement
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chambre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reservations as $reservation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($reservation->details->first()->chambre->medias->first())
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $reservation->details->first()->chambre->medias->first()->chemin_fichier) }}" alt="">
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $reservation->details->first()->chambre->typeChambre->nom }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                       Chambre {{ $reservation->details->first()->chambre->numero_chambre }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">au {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</div>
                                            <div class="text-sm text-gray-500">
                                                @if($reservation->acompte_paye > 0)
                                                    Acompte: {{ number_format($reservation->acompte_paye, 0, ',', ' ') }} FCFA
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'en_attente_paiement' => 'bg-amber-100 text-amber-800',
                                                    'acompte_paye' => 'bg-red-50 text-red-600',
                                                    'confirmee' => 'bg-red-600 text-white',
                                                    'annulee' => 'bg-gray-100 text-gray-800',
                                                    'terminee' => 'bg-gray-200 text-gray-500'
                                                ];
                                                
                                                $statusLabels = [
                                                    'en_attente_paiement' => 'En attente de paiement',
                                                    'acompte_paye' => 'Acompte payé',
                                                    'confirmee' => 'Confirmée',
                                                    'annulee' => 'Annulée',
                                                    'terminee' => 'Terminée'
                                                ];
                                                
                                                $statusClass = $statusClasses[$reservation->statut] ?? 'bg-gray-100 text-gray-800';
                                                $statusLabel = $statusLabels[$reservation->statut] ?? $reservation->statut;
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if(in_array($reservation->statut, ['brouillon', 'en_attente_paiement']))
                                                <a href="{{ route('reservations.continue', $reservation->id) }}" class="text-red-600 hover:text-red-900 mr-3">Continuer la réservation</a>
                                            @else
                                                <a href="{{ route('reservations.show', $reservation->id) }}" class="text-red-600 hover:text-red-900 mr-3">Détails</a>
                                            @endif
                                            
                                            @if(in_array($reservation->statut, ['en_attente_paiement']))
                                                <a href="{{ route('paiement.leekpay.initiate.reservation', $reservation->id) }}" class="text-green-600 hover:text-green-900 mr-3">Payer</a>
                                            @endif
                                            
                                            @if(in_array($reservation->statut, ['en_attente_paiement', 'acompte_paye']))
                                                <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">Annuler</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection