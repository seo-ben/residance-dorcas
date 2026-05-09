@extends('layouts.plaout')

@section('title', 'Résidence Dorcas | Location Appartements & Studios Meublés à Lomé')

@section('meta')
    <meta name="description" content="Découvrez la Résidence Dorcas à Lomé. Studios et appartements de luxe meublés avec Wi-Fi, Clim et Parking. Réservez votre séjour au cœur du quartier administratif.">
    <meta name="keywords" content="résidence Lomé, appartement meublé Lomé, studio meublé Togo, hébergement luxe Lomé, location courte durée Lomé">
    <meta property="og:title" content="Résidence Dorcas - Un séjour d'exception à Lomé">
    <meta property="og:description" content="Confort, sécurité et modernité au centre de Lomé. Réservez votre chambre maintenant.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('content')

    {{-- 1. ATTENTION: Hero & Search --}}
    <x-hero />

    {{-- 2. INTEREST: Apartment Showcase --}}
    <section class="py-20 lg:py-32 bg-white px-4 sm:px-6 lg:px-8" id="appartements">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 md:mb-16 gap-4 md:gap-6">
                <div class="max-w-2xl">
                    <span class="inline-block px-3 py-1 mb-2 text-[10px] sm:text-xs font-black tracking-[0.2em] md:tracking-[0.3em] text-red-600 border-l-4 border-red-600">
                        Notre Sélection Exclusive
                    </span>
                    {{-- <h2 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight">
                        Suites & Appartements <br> d'Exception
                    </h2> --}}
                </div>
                <div class="pb-2">
                    <a href="{{ route('chambres.index') }}" class="inline-flex items-center text-sm md:text-base text-gray-900 font-bold hover:text-red-600 transition-colors group">
                        Explorer la collection
                        <svg class="h-4 w-4 md:h-6 md:w-6 ml-1 md:ml-2 group-hover:translate-x-2 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <style>
                .hide-scroll-bar {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
                .hide-scroll-bar::-webkit-scrollbar {
                    display: none;
                }
            </style>
            
            <div class="relative group/slider -mx-4 px-4 sm:mx-0 sm:px-0">
                <div class="flex overflow-x-auto gap-4 md:gap-6 pb-8 pt-4 snap-x snap-mandatory hide-scroll-bar" id="room-slider">
                    @forelse($chambresPopulaires as $chambre)
                        <div class="snap-start w-72 sm:w-80 md:w-96 flex-shrink-0 first:pl-4 sm:first:pl-6 md:first:pl-8 last:pr-4 sm:last:pr-6 md:last:pr-8">
                            @include('partials.chambre-card', ['chambre' => $chambre])
                        </div>
                    @empty
                        <div class="w-full text-center py-12">
                            <p class="text-gray-500 text-lg">Préparation de nos plus beaux appartements...</p>
                        </div>
                    @endforelse
                </div>
                
                {{-- Optional Scroll Buttons (Desktop only for precision) --}}
                <button onclick="document.getElementById('room-slider').scrollBy({left: -300, behavior: 'smooth'})" class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-12 h-12 bg-white text-gray-900 rounded-full shadow-2xl items-center justify-center opacity-0 group-hover/slider:opacity-100 transition-opacity z-10 hover:bg-gray-50 border border-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button onclick="document.getElementById('room-slider').scrollBy({left: 300, behavior: 'smooth'})" class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-12 h-12 bg-white text-gray-900 rounded-full shadow-2xl items-center justify-center opacity-0 group-hover/slider:opacity-100 transition-opacity z-10 hover:bg-gray-50 border border-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
    </section>

    {{-- 3. STORYTELLING: L'Experience --}}
    <x-experience-storyline />

    {{-- 4. TRUST & LOCALIZATION --}}
    <x-location />
    
    <x-testimonials />

    {{-- 6. REASSURANCE --}}
    <x-faq />

    {{-- 7. ACTION --}}
    <x-cta />

    {{-- GALLERY --}}
    <section class="py-12 bg-gray-50 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Instantanés de la Résidence</h2>
        </div>
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([
                    'intérieure de la residence dorcas.webp', 
                    'interieur de la residence face de dorcas.webp', 
                    'cuisine de la residences dorcas.webp',
                    'IMG-20250624-WA0001.jpg'
                ] as $img)
                <div class="overflow-hidden rounded-xl h-48 md:h-64 shadow-md">
                    <img src="{{ asset('assets/images/' . $img) }}" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-500 cursor-pointer" alt="Résidence Dorcas Image">
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
@endpush