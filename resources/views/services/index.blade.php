@extends('layouts.plaout')

@section('title', 'Services d\'Exception | Résidence Dorcas')

@section('content')
    <!-- Hero Section Premium -->
    <div class="relative pt-12 pb-16 lg:pt-32 lg:pb-24 overflow-hidden bg-gray-900">
        <!-- Image de fond avec overlay progressif -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/images/intérieure de la residence dorcas.webp') }}" 
                 alt="Services Background" 
                 class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
         
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-8 leading-tight" data-aos="fade-up" data-aos-delay="100">
                Des Services <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-400">Sur Mesure</span> <br> pour un Séjour Inoubliable
            </h1>
            <p class="max-w-2xl mx-auto text-lg md:text-xl text-gray-300 leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                À la Résidence Dorcas, chaque détail compte. Découvrez notre gamme de services premium conçus pour répondre à toutes vos exigences de confort et de bien-être.
            </p>
        </div>
    </div>  

    <!-- Services Grid -->
    <section class="py-24 bg-white relative overflow-hidden" x-data="{ 
        showOrderModal: false, 
        selectedService: null,
        formData: {
            id_service: '',
            quantite: 1,
            date_service: '{{ date('Y-m-d') }}',
            heure_service: '{{ date('H:i') }}',
            notes: ''
        },
        openModal(service) {
            this.selectedService = service;
            this.formData.id_service = service.id;
            this.showOrderModal = true;
        },
        async submitOrder() {
            try {
                const response = await fetch('{{ route('services.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.formData)
                });
                const data = await response.json();
                if (response.ok) {
                    window.toast.success(data.message);
                    this.showOrderModal = false;
                    this.formData = { id_service: '', quantite: 1, date_service: '{{ date('Y-m-d') }}', heure_service: '{{ date('H:i') }}', notes: '' };
                } else {
                    window.toast.error(data.message || 'Une erreur est survenue.');
                }
            } catch (error) {
                console.error(error);
                window.toast.error('Erreur de connexion.');
            }
        }
    }">
        <!-- Éléments décoratifs en arrière-plan -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-red-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-96 h-96 bg-gray-50 rounded-full blur-3xl opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-12">
                @forelse($services as $index => $service)
                    <div class="group bg-white rounded-2xl p-4 sm:p-8 border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-red-500/10 transition-all duration-500 hover:-translate-y-2 flex flex-col" 
                         data-aos="fade-up" 
                         data-aos-delay="{{ $index * 100 }}">
                        
                        <!-- Icon Placeholder -->
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-red-50 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-8 group-hover:bg-red-600 group-hover:rotate-12 transition-all duration-500">
                            @if(Str::contains(Str::lower($service->nom), ['déjeuner', 'dîner']))
                                <i class="fas fa-utensils text-xl sm:text-2xl text-red-600 group-hover:text-white transition-colors"></i>
                            @elseif(Str::contains(Str::lower($service->nom), ['navette', 'voiture']))
                                <i class="fas fa-car text-xl sm:text-2xl text-red-600 group-hover:text-white transition-colors"></i>
                            @elseif(Str::contains(Str::lower($service->nom), ['blanchisserie', 'ménage']))
                                <i class="fas fa-tshirt text-xl sm:text-2xl text-red-600 group-hover:text-white transition-colors"></i>
                            @elseif(Str::contains(Str::lower($service->nom), ['massage', 'bien-être']))
                                <i class="fas fa-spa text-xl sm:text-2xl text-red-600 group-hover:text-white transition-colors"></i>
                            @else
                                <i class="fas fa-concierge-bell text-xl sm:text-2xl text-red-600 group-hover:text-white transition-colors"></i>
                            @endif
                        </div>

                        <h3 class="text-lg lg:text-2xl font-bold text-gray-900 mb-2 sm:mb-4 group-hover:text-red-600 transition-colors">
                            {{ $service->nom }}
                        </h3>
                        
                        <p class="text-xs sm:text-base text-gray-600 mb-4 sm:mb-8 leading-relaxed flex-grow line-clamp-2 sm:line-clamp-none">
                            {{ $service->description }}
                        </p>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-4 sm:pt-6 border-t border-gray-50 gap-2 sm:gap-0">
                            <div class="flex flex-col">
                                <span class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-wider mb-0.5 sm:mb-1">Tarif</span>
                                <span class="text-sm lg:text-xl font-black text-gray-900">
                                    {{ number_format($service->prix, 0, ',', ' ') }} <small class="text-[10px] sm:text-sm font-medium text-gray-500">FCFA</small>
                                </span>
                            </div>
                            
                            <div class="flex flex-col items-start sm:items-end text-left sm:text-right">
                                <span class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-wider mb-0.5 sm:mb-1">Disponibilité</span>
                                <span class="text-[10px] sm:text-sm font-bold text-gray-700">
                                    @if($service->disponibilite == '24h')
                                        24h/24
                                    @elseif($service->disponibilite == 'horaires_specifiques')
                                        {{ \Carbon\Carbon::parse($service->horaires_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($service->horaires_fin)->format('H:i') }}
                                    @else
                                        En journée
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Bouton d'action -->
                        <div class="mt-4 sm:mt-8">
                            <button @click="openModal({{ json_encode($service) }})" class="w-full py-2 sm:py-4 bg-gray-50 text-gray-900 text-sm sm:text-base font-bold rounded-xl sm:rounded-2xl group-hover:bg-red-600 group-hover:text-white transition-all duration-300 flex items-center justify-center gap-2">
                                Commander
                                <i class="fas fa-arrow-right text-[10px] sm:text-xs opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-box-open text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Nos services arrivent bientôt</h3>
                        <p class="text-gray-500">Nous peaufinons notre offre pour vous offrir la meilleure expérience possible.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal de Commande Premium -->
        <div x-show="showOrderModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm" @click="showOrderModal = false"></div>

            <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-gray-900">Commander</h2>
                            <p class="text-red-600 font-bold" x-text="selectedService?.nom"></p>
                        </div>
                        <button @click="showOrderModal = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form @submit.prevent="submitOrder()" class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Quantité</label>
                                <input type="number" x-model="formData.quantite" min="1" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Heure souhaitée</label>
                                <input type="time" x-model="formData.heure_service" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Date</label>
                            <input type="date" x-model="formData.date_service" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-gray-400 mb-2">Notes spécifiques</label>
                            <textarea x-model="formData.notes" rows="3" placeholder="Ex: Sans gluten, allergies, précisions pour la livraison..." class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all"></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 bg-red-600 text-white font-black rounded-2xl hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all transform hover:-translate-y-1">
                            Confirmer la commande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Engagement Premium -->
    <section class="py-24 bg-gray-900 overflow-hidden relative">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-red-600/10 skew-x-12 translate-x-1/4"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right">
                    <h2 class="text-3xl md:text-5xl font-black text-white mb-8 leading-tight">
                        Une Conciergerie <br> <span class="text-red-500">Dédiée à Votre Confort</span>
                    </h2>
                    <p class="text-gray-400 text-lg mb-10 leading-relaxed">
                        Que vous ayez besoin d'une réservation dans les meilleurs restaurants de Lomé, d'un chauffeur privé pour la journée ou simplement d'un oreiller supplémentaire, notre équipe est à votre entière disposition.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-red-600 transition-colors">
                                <i class="fas fa-check text-red-500 group-hover:text-white"></i>
                            </div>
                            <span class="text-white font-semibold">Service disponible 7j/7</span>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-red-600 transition-colors">
                                <i class="fas fa-check text-red-500 group-hover:text-white"></i>
                            </div>
                            <span class="text-white font-semibold">Équipe multilingue (Français, Anglais)</span>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-red-600 transition-colors">
                                <i class="fas fa-check text-red-500 group-hover:text-white"></i>
                            </div>
                            <span class="text-white font-semibold">Réponse immédiate garantie</span>
                        </div>
                    </div>
                </div>

                <div class="relative" data-aos="fade-left">
                    <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl">
                        <img src="{{ asset('assets/images/interieur de la residence face de dorcas.webp') }}" alt="Concierge service" class="w-full">
                    </div>
                    <!-- Élément flottant décoratif -->
                    <div class="absolute -bottom-8 -right-8 bg-white p-8 rounded-3xl shadow-2xl hidden md:block z-20" data-aos="zoom-in" data-aos-delay="400">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Assistance 24/7</p>
                                <p class="text-gray-900 font-black">+228 00 00 00 00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ & Contact Redirect -->
    <x-cta />

@endsection

@push('scripts')
<script>
    // Initialisation AOS (si pas déjà fait globalement)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    }
</script>
@endpush
