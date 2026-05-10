@extends('layouts.plaout')

@section('title', $vehicule->marque . ' ' . $vehicule->modele . ' - Residence Dorcas')

@section('content')
<div class="pb-16 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Back -->
        <nav class="mb-8 flex items-center justify-between">
            <a href="{{ route('vehicules.index') }}" class="inline-flex items-center text-sm font-black text-gray-400 hover:text-red-600 transition-colors tracking-widest">
                <i class="fas fa-arrow-left mr-2"></i> Retour au catalogue
            </a>
            <div class="hidden md:flex space-x-2">
                <span class="text-xs font-bold text-gray-300 tracking-widest">Véhicules</span>
                <span class="text-xs font-bold text-gray-300">/</span>
                <span class="text-xs font-bold text-red-600 tracking-widest">{{ $vehicule->marque }}</span>
            </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Column: Gallery & Description -->
            <div class="lg:col-span-7 space-y-8">
                <!-- Gallery Wrapper -->
                <div class="bg-white rounded-[2.5rem] p-4 shadow-xl shadow-gray-200/50" x-data="{ activeImage: '{{ $vehicule->primaryImage ? asset('storage/' . $vehicule->primaryImage->chemin_image) : '' }}' }">
                    <!-- Main Image -->
                    <div class="relative h-[300px] md:h-[450px] rounded-[2rem] overflow-hidden mb-4 bg-gray-100">
                        <template x-if="activeImage">
                            <img :src="activeImage" class="w-full h-full object-cover" alt="{{ $vehicule->marque }}">
                        </template>
                        <template x-if="!activeImage">
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-200">
                                <svg class="w-24 h-24 md:w-32 md:h-32 opacity-20" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                </svg>
                            </div>
                        </template>
                        
                        <!-- Badges Overlay -->
                        <div class="absolute top-6 left-6 flex flex-col gap-2">
                            <span class="px-5 py-2 bg-red-600 text-white rounded-full text-[10px] font-black tracking-widest shadow-lg">
                                {{ $vehicule->type }}
                            </span>
                            <span class="px-5 py-2 bg-white/90 backdrop-blur-md text-gray-900 rounded-full text-[10px] font-black tracking-widest shadow-lg">
                                {{ $vehicule->statut }}
                            </span>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    @if($vehicule->images->count() > 1)
                        <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide px-2">
                            @foreach($vehicule->images as $img)
                                <button @click="activeImage = '{{ asset('storage/' . $img->chemin_image) }}'" 
                                        class="flex-shrink-0 w-24 h-24 rounded-2xl overflow-hidden border-2 transition-all duration-300"
                                        :class="activeImage === '{{ asset('storage/' . $img->chemin_image) }}' ? 'border-red-600 scale-105 shadow-lg' : 'border-transparent opacity-60 hover:opacity-100'">
                                    <img src="{{ asset('storage/' . $img->chemin_image) }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Car Details -->
                <div class="bg-white rounded-[2.5rem] p-10 shadow-xl shadow-gray-200/50">
                    <h1 class="text-4xl font-black text-gray-900 mb-6">{{ $vehicule->marque }} {{ $vehicule->modele }}</h1>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
                        <div class="p-4 bg-gray-50 rounded-3xl">
                            <i class="fas fa-gas-pump text-red-600 mb-2"></i>
                            <span class="block text-[10px] font-black text-gray-400">Carburant</span>
                            <span class="font-bold text-gray-900">{{ $vehicule->carburant }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-3xl">
                            <i class="fas fa-cog text-red-600 mb-2"></i>
                            <span class="block text-[10px] font-black text-gray-400">Transmission</span>
                            <span class="font-bold text-gray-900">{{ $vehicule->transmission }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-3xl">
                            <i class="fas fa-users text-red-600 mb-2"></i>
                            <span class="block text-[10px] font-black text-gray-400">Places</span>
                            <span class="font-bold text-gray-900">{{ $vehicule->nb_places }}</span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-3xl">
                            <i class="fas fa-car text-red-600 mb-2"></i>
                            <span class="block text-[10px] font-black text-gray-400">Type</span>
                            <span class="font-bold text-gray-900">{{ $vehicule->type }}</span>
                        </div>
                    </div>

                    <div class="prose max-w-none text-gray-600 leading-relaxed mb-10">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 tracking-wider">Description</h3>
                        <p>{{ $vehicule->description ?? 'Ce véhicule premium offre un confort exceptionnel et une fiabilité inégalée pour tous vos trajets. Idéal pour vos déplacements professionnels ou vos sorties en famille.' }}</p>
                    </div>

                    <!-- Features -->
                    @if($vehicule->caracteristiques)
                        <h3 class="text-xl font-bold text-gray-900 mb-6 tracking-wider">Équipements & Options</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($vehicule->caracteristiques as $opt)
                                <div class="flex items-center text-gray-700 bg-gray-50 p-4 rounded-2xl">
                                    <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-check text-xs"></i>
                                    </div>
                                    <span class="font-bold">{{ $opt }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Booking Form -->
            <div class="lg:col-span-5">
                <div class="sticky top-28 space-y-6">
                    <div class="bg-gray-900 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-gray-400/20">
                        <div class="flex justify-between items-center mb-8">
                            <div>
                                <span class="block text-[10px] font-black text-gray-400 tracking-widest mb-1">Prix Journalier</span>
                                <span class="text-4xl font-black text-white">{{ number_format($vehicule->prix_journalier, 0, ',', ' ') }} <span class="text-sm font-bold text-gray-400">FCFA</span></span>
                            </div>
                            <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-600/30">
                                <i class="fas fa-calendar-alt text-2xl"></i>
                            </div>
                        </div>

                        <form action="{{ route('vehicules.book') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="id_vehicule" value="{{ $vehicule->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-gray-400 tracking-widest">Date de début</label>
                                    <input type="date" name="date_debut" required min="{{ date('Y-m-d') }}" class="w-full bg-white/10 border-white/20 rounded-2xl px-5 py-4 text-white focus:border-red-600 focus:ring-red-600 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-gray-400 tracking-widest">Date de fin</label>
                                    <input type="date" name="date_fin" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full bg-white/10 border-white/20 rounded-2xl px-5 py-4 text-white focus:border-red-600 focus:ring-red-600 transition-all">
                                </div>
                            </div>

                            @auth
                                @if(count($activeReservations) > 0)
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-gray-400 tracking-widest">Lier à une réservation</label>
                                        <select name="id_reservation" class="w-full bg-white/10 border-white/20 rounded-2xl px-5 py-4 text-white focus:border-red-600 focus:ring-red-600 transition-all">
                                            <option value="" class="bg-gray-900 text-white">Location indépendante</option>
                                            @foreach($activeReservations as $res)
                                                <option value="{{ $res->id }}" class="bg-gray-900 text-white">
                                                    Res #{{ $res->id }} - {{ $res->appartement->nom }} ({{ Carbon\Carbon::parse($res->date_arrivee)->format('d/m') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endauth

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-gray-400 tracking-widest">Notes particulières</label>
                                <textarea name="notes" rows="3" class="w-full bg-white/10 border-white/20 rounded-2xl px-5 py-4 text-white focus:border-red-600 focus:ring-red-600 transition-all" placeholder="Une demande spéciale ?"></textarea>
                            </div>

                            <button type="submit" class="w-full py-5 bg-red-600 hover:bg-red-700 text-white font-black rounded-2xl transition-all duration-300 tracking-[0.2em] text-sm shadow-xl shadow-red-600/30 flex items-center justify-center">
                                Réserver Maintenant
                                <i class="fas fa-bolt ml-3"></i>
                            </button>
                        </form>
                        
                        <p class="text-center mt-6 text-gray-500 text-xs font-bold tracking-widest">
                            <i class="fas fa-lock mr-2"></i> Paiement sécurisé via Stripe
                        </p>
                    </div>

                    <!-- Trust Badges -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white p-6 rounded-[2rem] text-center border border-gray-100">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-headset"></i>
                            </div>
                            <span class="text-[10px] font-black text-gray-900">Support 24/7</span>
                        </div>
                        <div class="bg-white p-6 rounded-[2rem] text-center border border-gray-100">
                            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <span class="text-[10px] font-black text-gray-900">Assurance Incluse</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
