@extends('layouts.plaout')

@section('title', 'Confirmation de réservation')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-red-50 to-white py-12" id="printable-content">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-100">
            <!-- En-tête avec animation de succès -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white text-center print:bg-green-600">
                <div class="rounded-full bg-white/20 p-4 mx-auto w-20 h-20 flex items-center justify-center mb-4 animate-bounce print:animate-none">
                    <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2">Réservation confirmée !</h1>
                <p class="text-white/90">Votre paiement a été traité avec succès</p>
            </div>

            <div class="p-6 sm:p-8">
                <!-- Détails de la réservation -->
                <div class="bg-red-50 rounded-xl p-6 mb-8 print:bg-white print:border print:border-red-200">
                    <h2 class="text-xl font-semibold text-red-900 mb-6 text-center">Détails de votre séjour</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                <p><span class="font-medium">Référence:</span> {{ $reservation->reference ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                <p><span class="font-medium">Code:</span> {{ $reservation->reference ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p><span class="font-medium">Arrivée:</span> 
                                        @if($reservation->date_arrivee)
                                            {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <p><span class="font-medium">Départ:</span> 
                                        @if($reservation->date_depart)
                                            {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @if($reservation->details && $reservation->details->isNotEmpty())
                                @php
                                    $premierDetail = $reservation->details->first();
                                @endphp
                                <div class="flex items-center space-x-3">
                                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <div>
                                        <p><span class="font-medium">Chambre:</span> 
                                            {{ $premierDetail->chambre->numero_chambre ?? 'N/A' }}
                                        </p>
                                        <p><span class="font-medium">Type:</span> 
                                            {{ $premierDetail->chambre->typeChambre->nom ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p><span class="font-medium">Propriété:</span> 
                                        {{ $premierDetail->chambre->propriete->nom ?? 'N/A' }}
                                    </p>
                                </div>
                            @endif
                            
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p><span class="font-medium">Montant payé:</span> 
                                        {{ number_format($paiement ? $paiement->montant : $reservation->prix_total, 0, ',', ' ') }} XOF
                                    </p>
                                    @if($paiement)
                                        <p class="text-sm text-gray-600">Payé le {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                @if(isset($qrCodeBase64))
                    <div class="text-center mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Votre QR Code de réservation</h3>
                        <p class="text-sm text-gray-600 mb-4">Présentez ce QR code à votre arrivée pour faciliter votre check-in</p>
                        <div class="inline-block p-3 bg-white border-2 border-red-100 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                            <img src="{{ $qrCodeBase64 }}" alt="QR Code de réservation" class="mx-auto">
                        </div>
                    </div>
                @endif

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 print:hidden">
                    <a href="{{ route('reservations.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg text-base font-medium text-white bg-red-600 hover:bg-red-700 transition-colors duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Retour à mes réservations
                    </a>
                    <button onclick="window.print()" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-base font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimer la confirmation
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printable-content,
    #printable-content * {
        visibility: visible;
    }
    #printable-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .print\:bg-white { background-color: white !important; }
    .print\:border { border-width: 1px !important; }
    .print\:border-red-200 { border-color: #fecaca !important; }
    .print\:animate-none { animation: none !important; }
    .print\:hidden { display: none !important; }
}
</style>
@endsection