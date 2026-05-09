@extends('layouts.plaout')

@section('title', 'À propos de la Résidence Dorcas | Excellence & Confort à Lomé')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <section class="relative h-[60vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('brain/01c72661-5ef8-4046-ad83-e7ed6cd9e76e/residence_dorcas_exterior_1777903506860.png') }}" 
                 alt="Résidence Dorcas" 
                 class="w-full h-full object-cover scale-105 animate-slow-zoom">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 to-transparent"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl" data-aos="fade-right">
                <span class="inline-block px-3 py-1 mb-4 text-[10px] font-black tracking-[0.3em] text-red-500 border-l-4 border-red-500 bg-white/10 backdrop-blur-sm">
                    NOTRE ESSENCE
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">
                    L'Art de Vivre <br>
                    <span class="text-red-500">à Lomé</span>
                </h1>
                <p class="text-lg text-gray-200 font-medium leading-relaxed mb-8">
                    Plus qu'une simple résidence, un sanctuaire de modernité et de confort niché au cœur de la capitale togolaise.
                </p>
            </div>
        </div>
    </section>

    <!-- Philosphy Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-up">
                    <h2 class="text-3xl font-black text-gray-900 mb-8 leading-tight">
                        Une Vision de l'Excellence <br>
                        <span class="text-red-600 text-xl font-bold tracking-widest uppercase">Depuis sa fondation</span>
                    </h2>
                    <div class="space-y-6">
                        <p class="text-gray-600 leading-relaxed">
                            La Résidence Dorcas est née d'une ambition simple : redéfinir les standards de l'hébergement meublé à Lomé. Nous avons créé un espace où le voyageur se sent chez lui dès la première seconde.
                        </p>
                        <p class="text-gray-600 leading-relaxed font-semibold">
                            Située à seulement 18 minutes de l'aéroport international Gnassingbé Eyadéma, notre position stratégique fait de nous le choix privilégié des professionnels et des familles en quête de sérénité.
                        </p>
                        <div class="pt-4 flex gap-4">
                            <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 flex-1">
                                <span class="block text-3xl font-black text-red-600">18 min</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">De l'aéroport</span>
                            </div>
                            <div class="text-center p-4 bg-white rounded-2xl shadow-sm border border-gray-100 flex-1">
                                <span class="block text-3xl font-black text-red-600">24/7</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sécurité</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4" data-aos="fade-left">
                    <div class="space-y-4">
                        <div class="h-64 rounded-3xl overflow-hidden shadow-2xl">
                            <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=2839&auto=format&fit=crop" class="w-full h-full object-cover" alt="Suite">
                        </div>
                        <div class="h-48 rounded-3xl overflow-hidden shadow-lg bg-red-600 flex items-center justify-center p-8 text-white">
                            <p class="text-lg font-bold italic">"Le confort n'est pas un luxe, c'est une nécessité."</p>
                        </div>
                    </div>
                    <div class="space-y-4 pt-12">
                        <div class="h-48 rounded-3xl overflow-hidden shadow-lg">
                            <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=2940&auto=format&fit=crop" class="w-full h-full object-cover" alt="Kitchen">
                        </div>
                        <div class="h-64 rounded-3xl overflow-hidden shadow-2xl">
                            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=2940&auto=format&fit=crop" class="w-full h-full object-cover" alt="Restaurant">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Grid -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-red-600 font-black text-xs uppercase tracking-[0.3em]">Nos Engagements</span>
                <h2 class="mt-4 text-3xl md:text-4xl font-black text-gray-900">Pourquoi choisir Dorcas ?</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="group p-8 rounded-[40px] bg-white border border-gray-100 hover:border-red-100 hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-500" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-4">Ultra Connecté</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Wi-Fi haut débit par fibre optique disponible dans toute la résidence pour vos besoins professionnels et personnels.
                    </p>
                </div>

                <!-- Value 2 -->
                <div class="group p-8 rounded-[40px] bg-white border border-gray-100 hover:border-red-100 hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-500" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-4">Cuisine d'Ailleurs</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Notre restaurant sur place vous propose une fusion entre gastronomie locale et saveurs internationales de 10h à 22h.
                    </p>
                </div>

                <!-- Value 3 -->
                <div class="group p-8 rounded-[40px] bg-white border border-gray-100 hover:border-red-100 hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-500" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-4">Sérénité Totale</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Système de surveillance avancé et service de conciergerie dédié pour assurer votre tranquillité d'esprit absolue.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-gray-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-red-600 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute bottom-0 -right-4 w-72 h-72 bg-red-900 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
        </div>
        
        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-8 leading-tight">Prêt à vivre l'expérience Dorcas ?</h2>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('chambres.index') }}" class="px-10 py-5 bg-red-600 hover:bg-red-700 text-white font-black uppercase tracking-widest text-xs rounded-2xl transition-all shadow-xl shadow-red-600/20 active:scale-95">
                    Réserver un appartement
                </a>
                <a href="{{ route('contact') }}" class="px-10 py-5 bg-white hover:bg-gray-100 text-gray-900 font-black uppercase tracking-widest text-xs rounded-2xl transition-all active:scale-95">
                    Nous contacter
                </a>
            </div>
        </div>
    </section>
</div>

<style>
    @keyframes slow-zoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }
    .animate-slow-zoom {
        animation: slow-zoom 20s infinite alternate linear;
    }
    
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endsection