<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<style>
    .flatpickr-calendar.inline {
        box-shadow: none !important;
        border: none !important;
        background: transparent;
        padding: 0;
        margin: 0 auto;
        width: 100% !important;
    }
    .flatpickr-months {
        margin-bottom: 10px;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

<div class="relative min-h-screen flex items-center justify-center overflow-hidden">
    {{-- Background Slider --}}
    <div class="absolute inset-0 z-0 overflow-hidden" id="hero-slider">
        <div class="absolute inset-0 bg-black/50 z-10"></div>
        
        <div class="slider-image active absolute inset-0 transition-opacity duration-[2000ms] ease-in-out opacity-100">
            <img src="{{ asset('assets/images/exterieur en face de la residence de dorcas.webp') }}" 
                 class="w-full h-full object-cover transform scale-105 animate-ken-burns" 
                 alt="Extérieur Résidence Dorcas 1">
        </div>
        
        <div class="slider-image absolute inset-0 transition-opacity duration-[2000ms] ease-in-out opacity-0">
            <img src="{{ asset('assets/images/exterieur de la résidence dorcas.jpg') }}" 
                 class="w-full h-full object-cover transform scale-105 animate-ken-burns" 
                 alt="Extérieur Résidence Dorcas 2">
        </div>
    </div>

    {{-- Content --}}
    <div class="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20">
        <div class="reveal-up">
            <h1 class="text-2xl md:text-4xl lg:text-5xl font-black text-white leading-tight mb-4 md:mb-8 drop-shadow-2xl">
                L'Excellence du Logement <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-300 to-white tracking-tighter">à Lomé</span>
            </h1>
            <p class="hidden md:block max-w-2xl mx-auto text-sm md:text-xl text-gray-200 mb-8 md:mb-12 font-medium leading-relaxed drop-shadow-md">
                Alliez élégance, confort moderne et sécurité au cœur de Lomé. 
                Une nouvelle vision de l'immobilier et de la location.
            </p>
        </div>

        {{-- Enhanced Search Bar (Glassmorphism) --}}
        <div class="mt-6 md:mt-10 reveal-up" style="transition-delay: 0.2s">
            <div class="glass-search rounded-2xl md:rounded-full bg-black/40 md:bg-white/10 backdrop-blur-2xl border border-white/20 shadow-2xl max-w-5xl mx-auto">
                <form method="GET" action="{{ route('chambres.index') }}" class="flex flex-col md:flex-row items-center gap-1 md:gap-2">
                    
                    {{-- Dates --}}
                    <div class="flex-1 w-full flex items-center px-4 py-3 md:px-6 md:py-4 border-b border-white/10 md:border-b-0 md:border-r group cursor-pointer" id="btnDateRange">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-red-400 mr-3 md:mr-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div class="text-left font-sans truncate">
                            <span class="block text-[9px] md:text-[10px] font-black text-red-300 tracking-widest">Durée souhaitée</span>
                            <span class="text-sm md:text-base text-white font-bold truncate" id="displayDates">Sélectionner les dates</span>
                        </div>
                    </div>

                    {{-- Voyageurs --}}
                    <div class="flex-1 w-full flex items-center px-4 py-3 md:px-6 md:py-4 border-b border-white/10 md:border-b-0 md:border-r group cursor-pointer" id="btnGuestCount">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-red-400 mr-3 md:mr-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <div class="text-left font-sans truncate">
                            <span class="block text-[9px] md:text-[10px] font-black text-red-300 tracking-widest">Voyageurs</span>
                            <span class="text-sm md:text-base text-white font-bold truncate" id="displayGuests">2 Adultes</span>
                        </div>
                    </div>

                    {{-- Bouton Réserver --}}
                    <div class="w-full md:w-auto p-1.5 md:p-1">
                        <button type="submit" class="w-full md:px-12 py-3.5 md:py-5 bg-red-600 hover:bg-red-500 text-white font-black rounded-xl md:rounded-full transition-all flex items-center justify-center gap-2 group overflow-hidden relative shadow-[0_0_20px_rgba(220,38,38,0.5)]">
                            <span class="relative z-10 tracking-widest text-xs md:text-sm">Trouver un bien</span>
                            <svg class="h-4 w-4 md:h-5 md:w-5 group-hover:translate-x-1 transition-transform relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    
                    <input type="hidden" name="date_arrivee" id="final_date_arrivee" value="{{ request('date_arrivee') }}">
                    <input type="hidden" name="date_depart" id="final_date_depart" value="{{ request('date_depart') }}">
                    <input type="hidden" name="capacite" id="final_capacite" value="{{ request('capacite', '2') }}">
                </form>
            </div>
        </div>
        
        {{-- Floating Social Content --}}
        <div class="mt-12 flex items-center justify-center gap-8 reveal-up" style="transition-delay: 0.4s">
            <div class="flex -space-x-3">
                @foreach([1,2,3,4] as $i)
                    <img class="w-10 h-10 rounded-full border-2 border-red-900 object-cover" src="https://i.pravatar.cc/100?u={{ $i }}" alt="User avatar">
                @endforeach
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 border-red-900 bg-red-800 text-xs font-bold text-white">+500</div>
            </div>
            <p class="text-xs font-bold text-gray-300 tracking-tighter text-left leading-tight">Biens visités ou loués <br> <span class="text-white">ces 30 derniers jours</span></p>
        </div>
    </div>
    
    {{-- Decorative Scroll Hint --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 animate-bounce cursor-pointer" onclick="document.getElementById('appartements').scrollIntoView({behavior: 'smooth'})">
        <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </div>
</div>

<style>
    .glass-search {
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.5);
    }
    
    .animate-ken-burns {
        animation: kenburns 30s infinite alternate linear;
    }

    @keyframes kenburns {
        from { transform: scale(1.0) translate(0,0); }
        to { transform: scale(1.2) translate(-2%, -2%); }
    }

    .slider-image.active {
        opacity: 1 !important;
        z-index: 5;
    }
    
    .reveal-up { opacity: 0; transform: translateY(40px); transition: all 1s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal-visible { opacity: 1 !important; transform: translateY(0); }
</style>

<!-- Unified Sidebar for Reservation (Date & Guests) -->
<!-- Unified Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-[100] transition-opacity duration-300 opacity-0"></div>

<!-- Date Sidebar -->
<div id="dateSidebar" class="sidebar-panel fixed top-0 right-0 h-screen w-full md:w-[400px] bg-white shadow-[-10px_0_40px_rgba(0,0,0,0.08)] z-[101] transform translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] flex flex-col border-l border-gray-200">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10 flex-shrink-0">
        <div>
            <h3 class="text-base font-black text-gray-900 tracking-tighter">Votre Séjour</h3>
            <p class="text-[9px] font-bold text-gray-400 tracking-[0.2em]">Période de réservation</p>
        </div>
        <button type="button" class="closeSidebar text-gray-400 hover:text-gray-900 transition-colors bg-gray-50 hover:bg-gray-100 p-2.5 rounded-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    
    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50/30">
        <!-- Flatpickr Inline Calendar -->
        <div class="w-full mb-4 flex justify-center rounded-xl bg-white p-3 border border-gray-100 shadow-sm" style="min-height: 280px;">
            <div id="calendar_container" class="w-full max-w-full overflow-hidden flex justify-center">
                <input type="text" id="date_range_picker" style="display:none;">
            </div>
        </div>
        
        <div class="flex items-center justify-between bg-white shadow-sm border border-gray-100 p-3.5 rounded-xl">
            <div class="w-1/2">
                <span class="block text-[9px] font-bold text-gray-400 tracking-[0.1em] mb-1">Check-in</span>
                <span id="modal_display_arr" class="font-bold text-gray-900 text-sm">-</span>
            </div>
            <div class="h-6 w-px bg-gray-200 mx-3"></div>
            <div class="w-1/2 text-right">
                <span class="block text-[9px] font-bold text-gray-400 tracking-[0.1em] mb-1">Check-out</span>
                <span id="modal_display_dep" class="font-bold text-gray-900 text-sm">-</span>
            </div>
        </div>
    </div>
    
    <!-- Footer (Sticky Bottom) -->
    <div class="p-5 border-t border-gray-100 bg-white shadow-[0_-5px_15px_rgba(0,0,0,0.02)] flex-shrink-0 z-10">
        <button type="button" id="applyDateSidebar" class="w-full bg-gray-900 text-white py-3.5 rounded-xl text-xs font-black tracking-[0.1em] shadow-lg hover:bg-red-600 hover:shadow-red-500/30 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">Valider les dates</button>
    </div>
</div>

<!-- Guest Sidebar -->
<div id="guestSidebar" class="sidebar-panel fixed top-0 right-0 h-screen w-full md:w-[350px] bg-white shadow-[-10px_0_40px_rgba(0,0,0,0.08)] z-[101] transform translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] flex flex-col border-l border-gray-200">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10 flex-shrink-0">
        <div>
            <h3 class="text-base font-black text-gray-900 tracking-tighter">Voyageurs</h3>
            <p class="text-[9px] font-bold text-gray-400 tracking-[0.2em]">Participants au séjour</p>
        </div>
        <button type="button" class="closeSidebar text-gray-400 hover:text-gray-900 transition-colors bg-gray-50 hover:bg-gray-100 p-2.5 rounded-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    
    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto p-6 bg-gray-50/30">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <span class="block font-black text-gray-900 text-sm tracking-tight">Adultes</span>
                    <span class="text-[9px] font-bold text-gray-400 tracking-widest mt-0.5">Dès 13 ans</span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" id="adultsDown" class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-lg font-bold text-gray-500 hover:border-red-600 hover:text-red-600 transition-colors">-</button>
                    <span id="adultsCount" class="font-bold text-sm w-4 text-center text-gray-900">2</span>
                    <button type="button" id="adultsUp" class="w-8 h-8 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition-colors">+</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="p-5 border-t border-gray-100 bg-white shadow-[0_-5px_15px_rgba(0,0,0,0.02)] flex-shrink-0 z-10">
        <button type="button" id="applyGuestSidebar" class="w-full bg-gray-900 text-white py-3.5 rounded-xl text-xs font-black tracking-[0.1em] shadow-lg hover:bg-red-600 hover:shadow-red-500/30 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">Valider les voyageurs</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('sidebarOverlay');
    const sidebars = {
        date: document.getElementById('dateSidebar'),
        guest: document.getElementById('guestSidebar')
    };

    const triggers = {
        date: document.getElementById('btnDateRange'), 
        guest: document.getElementById('btnGuestCount')
    };

    let activeSidebar = null;

    function openSidebar(type) {
        activeSidebar = sidebars[type];
        overlay.classList.remove('hidden');
        void overlay.offsetWidth; // prompt reflow
        overlay.classList.remove('opacity-0');
        activeSidebar.classList.remove('translate-x-full');
    }

    function closeSidebar() {
        if(activeSidebar) {
            activeSidebar.classList.add('translate-x-full');
            activeSidebar = null;
        }
        overlay.classList.add('opacity-0');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    triggers.date.addEventListener('click', () => openSidebar('date'));
    triggers.guest.addEventListener('click', () => openSidebar('guest'));

    document.querySelectorAll('.closeSidebar').forEach(btn => btn.addEventListener('click', closeSidebar));
    overlay.addEventListener('click', closeSidebar);

    // Initial Reveal
    setTimeout(() => {
        document.querySelectorAll('.reveal-up').forEach(el => el.classList.add('reveal-visible'));
    }, 200);

    // Slider Logic
    const images = document.querySelectorAll('.slider-image');
    let idx = 0;
    setInterval(() => {
        images[idx].classList.remove('active', 'opacity-100');
        images[idx].classList.add('opacity-0');
        idx = (idx + 1) % images.length;
        images[idx].classList.add('active', 'opacity-100');
        images[idx].classList.remove('opacity-0');
    }, 7000);

    // Flatpickr Calendar Logic
    let selectedArrStr = '';
    let selectedDepStr = '';
    
    flatpickr("#date_range_picker", {
        mode: "range",
        inline: true,
        minDate: "today",
        locale: "fr",
        showMonths: 1, // Fixed to 1 month to prevent overflowing and squishing inside the modal container
        onChange: function(selectedDates, dateStr, instance) {
            const applyBtn = document.getElementById('applyDatesModal');
            if (selectedDates.length === 2) {
                // Formatting for display
                const arrDisplay = instance.formatDate(selectedDates[0], "d M Y");
                const depDisplay = instance.formatDate(selectedDates[1], "d M Y");
                
                // Formatting for form values (YYYY-MM-DD)
                selectedArrStr = instance.formatDate(selectedDates[0], "Y-m-d");
                selectedDepStr = instance.formatDate(selectedDates[1], "Y-m-d");

                document.getElementById('modal_display_arr').innerText = arrDisplay;
                document.getElementById('modal_display_dep').innerText = depDisplay;
                applyBtn.disabled = false;
            } else {
                document.getElementById('modal_display_arr').innerText = "-";
                document.getElementById('modal_display_dep').innerText = "-";
                selectedArrStr = '';
                selectedDepStr = '';
                applyBtn.disabled = true;
            }
        }
    });

    // Apply Logic for Date Sidebar
    document.getElementById('applyDateSidebar').addEventListener('click', () => {
        if(selectedArrStr && selectedDepStr) {
            document.getElementById('final_date_arrivee').value = selectedArrStr;
            document.getElementById('final_date_depart').value = selectedDepStr;
            const arrParts = selectedArrStr.split('-');
            const depParts = selectedDepStr.split('-');
            document.getElementById('displayDates').innerText = `${arrParts[2]}/${arrParts[1]} → ${depParts[2]}/${depParts[1]}`;
        }
        closeSidebar();
    });

    let count = 2;
    document.getElementById('adultsUp').addEventListener('click', () => { 
        count++; document.getElementById('adultsCount').innerText = count; 
    });
    document.getElementById('adultsDown').addEventListener('click', () => { 
        if(count > 1) { count--; document.getElementById('adultsCount').innerText = count; }
    });

    // Apply Logic for Guest Sidebar
    document.getElementById('applyGuestSidebar').addEventListener('click', () => {
        document.getElementById('final_capacite').value = count;
        document.getElementById('displayGuests').innerText = `${count} Adultes`;
        closeSidebar();
    });

    // Initial Reveal
    setTimeout(() => {
        document.querySelectorAll('.reveal-up').forEach(el => el.classList.add('reveal-visible'));
    }, 200);
});
</script>
