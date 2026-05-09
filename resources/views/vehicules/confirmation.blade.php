@extends('layouts.plaout')

@section('title', 'Confirmation de Réservation - Residence Dorcas')

@section('content')
<div class="pt-32 pb-24 min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-[3rem] p-12 shadow-2xl shadow-gray-200/50 text-center relative overflow-hidden" data-aos="zoom-in">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-red-600/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-red-600/5 rounded-full blur-3xl"></div>

            <!-- Success Icon -->
            <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                <i class="fas fa-check text-4xl"></i>
            </div>

            <h1 class="text-4xl font-black text-gray-900 mb-4 uppercase tracking-tight">Demande Enregistrée !</h1>
            <p class="text-gray-500 text-lg mb-12 font-medium">Merci pour votre confiance. Votre demande de location pour le véhicule <span class="text-red-600 font-bold">{{ $location->vehicule->marque }} {{ $location->vehicule->modele }}</span> est en cours de traitement.</p>

            <!-- Summary Card -->
            <div class="bg-gray-50 rounded-[2.5rem] p-8 mb-12 text-left border border-gray-100">
                <div class="flex flex-col md:flex-row items-center gap-8 mb-8 pb-8 border-b border-gray-200">
                    <div class="w-full md:w-48 h-32 rounded-3xl overflow-hidden shadow-lg flex-shrink-0">
                        @if($location->vehicule->primaryImage)
                            <img src="{{ asset('storage/' . $location->vehicule->primaryImage->chemin_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fas fa-car text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Véhicule</span>
                        <h2 class="text-2xl font-black text-gray-900">{{ $location->vehicule->marque }} {{ $location->vehicule->modele }}</h2>
                        <div class="flex items-center text-red-600 font-bold mt-1">
                            <i class="fas fa-id-card mr-2"></i> {{ $location->vehicule->immatriculation }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-400 mr-4 shadow-sm">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Période</span>
                                <span class="font-bold text-gray-900">Du {{ \Carbon\Carbon::parse($location->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($location->date_fin)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-400 mr-4 shadow-sm">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Total estimé</span>
                                <span class="font-bold text-red-600 text-xl">{{ number_format($location->prix_total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-400 mr-4 shadow-sm">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">N° de Dossier</span>
                                <span class="font-bold text-gray-900">#LOC-{{ str_pad($location->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-400 mr-4 shadow-sm">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Statut</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-orange-100 text-orange-600 uppercase tracking-widest mt-1">
                                    En Attente de Validation
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center px-10 py-5 bg-gray-900 text-white font-black rounded-3xl hover:bg-red-600 transition-all duration-300 uppercase tracking-[0.2em] text-sm shadow-xl shadow-gray-200">
                    Retour à l'Accueil
                </a>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest pt-4">
                    Un conseiller vous contactera sous peu pour finaliser le paiement et la remise des clés.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
