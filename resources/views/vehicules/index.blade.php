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
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4 md:gap-10">
            @forelse($vehicules as $vehicule)
                <div class="bg-white rounded-2xl md:rounded-[2rem] overflow-hidden shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-red-200/40 transition-all duration-500 group flex flex-col h-full" data-aos="fade-up">
                    <!-- Image Wrapper -->
                    <div class="relative h-40 md:h-64 overflow-hidden">
                        @if($vehicule->primaryImage)
                            <img src="{{ asset('storage/' . $vehicule->primaryImage->chemin_image) }}" 
                                 alt="{{ $vehicule->marque }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gray-50 flex flex-col items-center justify-center text-gray-200 p-4">
                                <svg class="w-16 h-16 md:w-24 md:h-24 opacity-20" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Badge -->
                        <div class="absolute top-3 left-3 md:top-6 md:left-6">
                            <span class="px-2 py-1 md:px-4 md:py-2 bg-white/90 backdrop-blur-md rounded-full text-[8px] md:text-[10px] font-black text-red-600 tracking-widest shadow-lg">
                                {{ $vehicule->type }}
                            </span>
                        </div>
                        
                        <!-- Price Overlay -->
                        <div class="absolute bottom-3 right-3 md:bottom-6 md:right-6">
                            <div class="bg-red-600 text-white px-3 py-2 md:px-5 md:py-3 rounded-xl md:rounded-2xl shadow-xl transform translate-y-1 group-hover:translate-y-0 transition-transform duration-500">
                                <span class="text-sm md:text-xl font-black">{{ number_format($vehicule->prix_journalier, 0, ',', ' ') }}</span>
                                <span class="text-[8px] md:text-[10px] font-bold opacity-80 block leading-tight">CFA / j</span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 md:p-8 flex-grow flex flex-col">
                        <div class="mb-3 md:mb-6">
                            <h3 class="text-sm md:text-2xl font-black text-gray-900 mb-1 group-hover:text-red-600 transition-colors truncate">{{ $vehicule->marque }} {{ $vehicule->modele }}</h3>
                            <div class="flex items-center text-gray-400 text-[8px] md:text-sm font-bold tracking-widest">
                                <i class="fas fa-id-card mr-1 md:mr-2 text-red-500"></i>
                                {{ $vehicule->immatriculation }}
                            </div>
                        </div>

                        <!-- Technical Specs (Simplified for mobile) -->
                        <div class="grid grid-cols-3 gap-2 md:gap-4 mb-4 md:mb-8">
                            <div class="text-center p-1.5 md:p-3 bg-gray-50 rounded-lg md:rounded-2xl">
                                <i class="fas fa-users text-red-500 text-[10px] md:text-base mb-0.5 md:mb-1"></i>
                                <span class="block text-[8px] md:text-[10px] font-black text-gray-400">{{ $vehicule->nb_places }} pl.</span>
                            </div>
                            <div class="text-center p-1.5 md:p-3 bg-gray-50 rounded-lg md:rounded-2xl">
                                <i class="fas fa-cog text-red-500 text-[10px] md:text-base mb-0.5 md:mb-1"></i>
                                <span class="block text-[8px] md:text-[10px] font-black text-gray-400 truncate">{{ Str::limit($vehicule->transmission, 4) }}</span>
                            </div>
                            <div class="text-center p-1.5 md:p-3 bg-gray-50 rounded-lg md:rounded-2xl">
                                <i class="fas fa-gas-pump text-red-500 text-[10px] md:text-base mb-0.5 md:mb-1"></i>
                                <span class="block text-[8px] md:text-[10px] font-black text-gray-400 truncate">{{ Str::limit($vehicule->carburant, 3) }}</span>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="mt-auto">
                            <a href="{{ route('vehicules.show', $vehicule->id) }}" class="w-full inline-flex items-center justify-center px-3 py-3 md:px-8 md:py-4 bg-gray-900 text-white font-black rounded-xl md:rounded-2xl hover:bg-red-600 transition-all duration-300 tracking-widest text-[8px] md:text-sm shadow-lg shadow-gray-200 uppercase">
                                Détails
                                <i class="fas fa-arrow-right ml-2 md:ml-3 group-hover:translate-x-2 transition-transform"></i>
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
