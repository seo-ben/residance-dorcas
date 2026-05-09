<div class="bg-white rounded-2xl md:rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 group border border-gray-100 flex flex-col h-full">
    {{-- Image Section with Badges --}}
    <div class="relative overflow-hidden h-40 sm:h-48 md:h-72">
        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-500 z-10"></div>
        
        @php
            $image = $chambre->medias->first() ? Storage::url($chambre->medias->first()->chemin_fichier) : asset('assets/images/exterieur de la résidence dorcas.jpg');
        @endphp
        
        <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 bg-gray-100" 
             src="{{ $image }}" 
             onerror="this.src='{{ asset('assets/images/exterieur de la résidence dorcas.jpg') }}';"
             alt="{{ $chambre->typeChambre->nom ?? 'Chambre' }} {{ $chambre->numero_chambre }}">

        {{-- Floating Top Badges (Smaller on mobile) --}}
        <div class="absolute top-2 left-2 md:top-4 md:left-4 z-20 flex flex-col gap-1 md:gap-2">
            @if($chambre->est_populaire)
                <span class="bg-amber-400 text-amber-950 text-[8px] md:text-[10px] font-black uppercase tracking-widest px-2 py-1 md:px-3 md:py-1.5 rounded-full shadow-lg backdrop-blur-md">
                    Coup de cœur
                </span>
            @endif
        </div>

        {{-- Price Badge (Smaller on mobile) --}}
        <div class="absolute bottom-2 right-2 md:bottom-4 md:right-4 z-20">
            <div class="bg-red-600 text-white px-2 py-1 md:px-4 md:py-2 rounded-lg md:rounded-2xl shadow-xl">
                <span class="text-[8px] md:text-xs block opacity-80 font-bold uppercase tracking-tighter">Dès</span>
                <span class="text-sm md:text-xl font-black">{{ number_format($chambre->prix_base, 0, ',', ' ') }} <small class="text-[8px] md:text-[10px]">CFA</small></span>
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="p-4 md:p-8 flex flex-col flex-1">
        <div class="flex justify-between items-start mb-2 md:mb-4">
            <div class="min-w-0 flex-1">
                <h3 class="text-sm md:text-2xl font-black text-gray-900 group-hover:text-red-600 transition-colors leading-tight mb-1 truncate">
                    {{ $chambre->typeChambre->nom ?? 'Chambre' }}
                </h3>
                <div class="flex items-center text-gray-400 text-[10px] md:text-sm font-medium">
                    <svg class="h-3 w-3 md:h-4 md:w-4 mr-1 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">N°{{ $chambre->numero_chambre }}</span>
                </div>
            </div>
        </div>

        {{-- Features Pills (Hidden on very small screens or limited) --}}
        <div class="hidden sm:flex flex-wrap gap-1 md:gap-2 mb-4 md:mb-8">
            @foreach($chambre->equipements->take(2) as $equip)
                <span class="inline-flex items-center px-2 py-0.5 md:px-3 md:py-1 bg-gray-50 border border-gray-100 rounded-md md:rounded-lg text-gray-600 text-[8px] md:text-[11px] font-bold uppercase tracking-tight">
                    {{ $equip->nom }}
                </span>
            @endforeach
        </div>

        {{-- Footer Logic --}}
        <div class="mt-auto pt-3 md:pt-6 border-t border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full {{ $chambre->statut === 'disponible' ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></span>
                <span class="text-[8px] md:text-xs font-bold {{ $chambre->statut === 'disponible' ? 'text-green-600' : 'text-red-600' }} uppercase tracking-widest">
                    {{ $chambre->statut === 'disponible' ? 'Libre' : 'Occupé' }}
                </span>
            </div>

            <a href="{{ route('chambres.show', $chambre->id) }}" 
               class="inline-flex items-center text-red-600 font-black text-[10px] md:text-sm uppercase tracking-widest hover:translate-x-1 transition-transform">
                <span class="hidden md:inline">Explorer</span>
                <svg class="h-4 w-4 md:h-5 md:w-5 ml-1 md:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</div>
