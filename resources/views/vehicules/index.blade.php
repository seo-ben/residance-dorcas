@extends('layouts.plaout')

@section('title', 'Location de Voiture - Residence Dorcas')

@section('content')
<div class="pb-16 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative py-20 bg-red-600 overflow-hidden mb-12">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 L100 0 L100 100 Z" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight" data-aos="fade-up">
                Liberté & Confort
            </h1>
            <p class="text-xl text-red-100 max-w-2xl mx-auto font-medium" data-aos="fade-up" data-aos-delay="100">
                Découvrez notre flotte de véhicules premium pour vos déplacements en toute sérénité.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters (Simple for now) -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-12">
            <h2 class="text-2xl font-black text-gray-900 tracking-wider">Véhicules Disponibles</h2>
            <div class="flex items-center space-x-2 text-sm font-bold text-gray-400">
                <span>{{ $vehicules->count() }} options trouvées</span>
            </div>
        </div>

        <!-- Vehicles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($vehicules as $vehicule)
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-red-200/40 transition-all duration-500 group flex flex-col h-full" data-aos="fade-up">
                    <!-- Image Wrapper -->
                    <div class="relative h-64 overflow-hidden">
                        @if($vehicule->primaryImage)
                            <img src="{{ asset('storage/' . $vehicule->primaryImage->chemin_image) }}" 
                                 alt="{{ $vehicule->marque }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                                <i class="fas fa-car text-6xl"></i>
                            </div>
                        @endif
                        
                        <!-- Badge -->
                        <div class="absolute top-6 left-6">
                            <span class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-black text-red-600 tracking-widest shadow-lg">
                                {{ $vehicule->type }}
                            </span>
                        </div>
                        
                        <!-- Price Overlay -->
                        <div class="absolute bottom-6 right-6">
                            <div class="bg-red-600 text-white px-5 py-3 rounded-2xl shadow-xl transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                                <span class="text-xl font-black">{{ number_format($vehicule->prix_journalier, 0, ',', ' ') }}</span>
                                <span class="text-[10px] font-bold opacity-80 block leading-tight">FCFA / jour</span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-8 flex-grow flex flex-col">
                        <div class="mb-6">
                            <h3 class="text-2xl font-black text-gray-900 mb-2 group-hover:text-red-600 transition-colors">{{ $vehicule->marque }} {{ $vehicule->modele }}</h3>
                            <div class="flex items-center text-gray-400 text-sm font-bold tracking-widest">
                                <i class="fas fa-id-card mr-2 text-red-500"></i>
                                {{ $vehicule->immatriculation }}
                            </div>
                        </div>

                        <!-- Technical Specs -->
                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="text-center p-3 bg-gray-50 rounded-2xl">
                                <i class="fas fa-users text-red-500 mb-1"></i>
                                <span class="block text-[10px] font-black text-gray-400">Places : {{ $vehicule->nb_places }}</span>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-2xl">
                                <i class="fas fa-cog text-red-500 mb-1"></i>
                                <span class="block text-[10px] font-black text-gray-400">{{ $vehicule->transmission }}</span>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-2xl">
                                <i class="fas fa-gas-pump text-red-500 mb-1"></i>
                                <span class="block text-[10px] font-black text-gray-400 text-xs">{{ $vehicule->carburant }}</span>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="mt-auto">
                            <a href="{{ route('vehicules.show', $vehicule->id) }}" class="w-full inline-flex items-center justify-center px-8 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-red-600 transition-all duration-300 tracking-widest text-sm shadow-lg shadow-gray-200">
                                Détails & Réservation
                                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-car-side text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Aucun véhicule disponible</h3>
                    <p class="text-gray-500 mt-2">Revenez plus tard pour voir nos nouvelles offres.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .smoke { animation: float 3s ease-in-out infinite; }
</style>
@endsection
