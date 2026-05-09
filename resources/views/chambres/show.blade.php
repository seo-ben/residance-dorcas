@extends('layouts.plaout')

@section('title', 'Appartement '.$chambre->numero_chambre)

@section('content')

<div class="bg-gray-50 py-6 flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <!-- Image Gallery avec bouton pour voir toutes les photos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 relative">
            <div class="relative h-96">
                @if($chambre->medias->first())
                    <img src="{{ Storage::url($chambre->medias->first()->chemin_fichier) }}" 
                         alt="{{ $chambre->typeChambre->nom }}"
                         class="w-full h-full object-cover rounded-lg">
                @endif
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach($chambre->medias->skip(1)->take(4) as $media)
                    <div class="relative h-44">
                        <img src="{{ Storage::url($media->chemin_fichier) }}" 
                             alt="{{ $chambre->typeChambre->nom }}"
                             class="w-full h-full object-cover rounded-lg">
                    </div>
                @endforeach
            </div>
            @if($chambre->medias->count() > 5)
                <button onclick="openGallery()" class="absolute bottom-4 right-4 bg-white bg-opacity-80 text-red-600 px-4 py-2 rounded-md font-medium hover:bg-opacity-100 transition">
                    Voir toutes les photos ({{ $chambre->medias->count() }})
                </button>
            @endif
        </div>

        <!-- Room Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Appartement {{ $chambre->numero_chambre }}
                    </h1>
                    <div class="share-options hidden absolute bg-white shadow-lg rounded-lg p-4 z-10">
                        <a href="https://wa.me/?text={{ urlencode($chambre->typeChambre->nom . ' : ' . route('chambres.show', $chambre->id)) }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                            <i class="fab fa-whatsapp text-green-500"></i> Partager via WhatsApp
                        </a>
                        <a href="mailto:?subject={{ urlencode($chambre->typeChambre->nom) }}&body={{ urlencode(route('chambres.show', $chambre->id)) }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                            <i class="fas fa-envelope text-blue-500"></i> Partager par Email
                        </a>
                        <button onclick="copyToClipboard('{{ route('chambres.show', $chambre->id) }}')"
                                class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                            <i class="fas fa-copy text-gray-500"></i> Copier le lien
                        </button>
                    </div>
                </div>
                
                <!-- Type de chambre et statut -->
                <div class="flex items-center mb-4">
                    <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full mr-2">
                        {{ $chambre->typeChambre->nom }}
                    </span>
                    @if($chambre->statut)
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                            Disponible
                        </span>
                    @else
                        <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                            Non disponible
                        </span>
                    @endif
                </div>
                
                <p class="text-gray-600 mb-6">{{ $chambre->description }}</p>

                <!-- Points forts -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-ruler-combined text-red-600 mr-2"></i>
                        <span>{{ $chambre->typeChambre->superficie }} m²</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user-friends text-red-600 mr-2"></i>
                        <span>{{ $chambre->typeChambre->capacite_max }} personnes max</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-bed text-red-600 mr-2"></i>
                        <span>{{ $chambre->typeChambre->nombre_lits }} lit(s)</span>
                    </div>
          
                </div>

                <!-- Amenities -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Équipements</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($chambre->equipements as $equipement)
                            <div class="flex items-center">
                                <i class="fas fa-{{ $equipement->icone }} text-red-600 mr-2"></i>
                                <span>{{ $equipement->nom }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Politique de réservation -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Politique de réservation</h2>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-clock text-red-600 mr-2 mt-1"></i>
                            <div>
                                <span class="font-medium">Check-in / Check-out</span>
                                <p class="text-gray-600">Arrivée à partir de 14h, départ avant 11h</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-ban text-red-600 mr-2 mt-1"></i>
                            <div>
                                <span class="font-medium">Annulation</span>
                                <p class="text-gray-600">Annulation gratuite jusqu'à 48h avant l'arrivée</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-money-bill text-red-600 mr-2 mt-1"></i>
                            <div>
                                <span class="font-medium">Paiement</span>
                                <p class="text-gray-600">Prépaiement de 30% à la réservation, solde sur place</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <!-- Location avec carte -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Emplacement</h2>
                    <p class="flex items-center text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                        {{ $chambre->propriete->adresse }}
                    </p>
                    <div class="h-64 bg-gray-200 rounded-lg mb-4">
                        <!-- Intégrer une carte ici (Google Maps, Leaflet, etc.) -->
                        <div id="map" class="w-full h-full rounded-lg"></div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-utensils text-red-600 mr-2"></i>
                            <span>Restaurants à proximité</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-subway text-red-600 mr-2"></i>
                            <span>Transport à 500m</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shopping-bag text-red-600 mr-2"></i>
                            <span>Centre commercial</span>
                        </div>
                    </div>
                </div> --}}

                <!-- Avis et évaluations -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold">Avis ({{ $chambre->avis->count() }})</h2>
                        <div class="flex items-center">
                            <div class="flex items-center mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($chambre->note_moyenne))
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-yellow-400"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="font-medium">{{ number_format($chambre->note_moyenne, 1) }}/5</span>
                        </div>
                    </div>
                    
                    @foreach($chambre->avis->take(3) as $avis)
                        <div class="border-b border-gray-200 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium">{{ $avis->utilisateur->nom }}</h3>
                                    <span class="text-sm text-gray-500">{{ $avis->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $avis->note)
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @else
                                            <i class="far fa-star text-yellow-400"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-600 mt-2">{{ $avis->commentaire }}</p>
                        </div>
                    @endforeach
                    
                    @if($chambre->avis->count() > 3)
                        <a href="{{-- route('chambre.avis', $chambre->id) --}}" class="text-red-600 font-medium hover:text-red-800 transition">
                            Voir tous les avis
                        </a>
                    @endif
                </div>

                <!-- FAQ -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Questions fréquentes</h2>
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 pb-4">
                            <button class="flex justify-between items-center w-full text-left" onclick="toggleFaq(this)">
                                <span class="font-medium">Y a-t-il un service de ménage quotidien ?</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </button>
                            <div class="faq-answer hidden mt-2">
                                <p class="text-gray-600">Oui, un service de ménage est effectué tous les jours entre 10h et 14h.</p>
                            </div>
                        </div>
                        <div class="border-b border-gray-200 pb-4">
                            <button class="flex justify-between items-center w-full text-left" onclick="toggleFaq(this)">
                                <span class="font-medium">Puis-je demander un lit supplémentaire ?</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </button>
                            <div class="faq-answer hidden mt-2">
                                <p class="text-gray-600">Oui, vous pouvez demander un lit supplémentaire moyennant un supplément de 10 000 FCFA par nuit.</p>
                            </div>
                        </div>
                        <div>
                            <button class="flex justify-between items-center w-full text-left" onclick="toggleFaq(this)">
                                <span class="font-medium">Les animaux sont-ils autorisés ?</span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </button>
                            <div class="faq-answer hidden mt-2">
                                <p class="text-gray-600">Non, les animaux ne sont pas autorisés dans notre établissement.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards (Dynamic based on type_location) -->
            <div class="space-y-6">
                <!-- Short Term Booking Card -->
                @if(in_array($chambre->type_location, ['courte_duree', 'mixte']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <div class="mb-5 flex flex-col justify-start">
                        <span class="text-3xl font-black text-red-600">{{ number_format($chambre->prix_base, 0, ',', ' ') }} <span class="text-sm font-bold text-gray-500">FCFA</span></span>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">/ Nuit (SÉJOUR COURT)</span>
                    </div>
                    <form action="{{ route('reservations.create') }}" method="GET" class="space-y-4">
                        <input type="hidden" name="chambre_id" value="{{ $chambre->id }}">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Check-in</label>
                                <input type="date" name="date_arrivee" required class="w-full text-xs border border-gray-200 rounded-lg p-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-gray-50 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Check-out</label>
                                <input type="date" name="date_depart" required class="w-full text-xs border border-gray-200 rounded-lg p-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-gray-50 transition-colors">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-red-600 text-white font-bold uppercase tracking-wider text-[11px] px-4 py-3.5 rounded-xl hover:bg-red-700 transition-colors shadow-md hover:shadow-lg flex justify-center items-center gap-2">
                            <i class="fas fa-bolt"></i> Réserver maintenant
                        </button>
                    </form>
                </div>
                @endif

                <!-- Long Term Visit Card -->
                @if(in_array($chambre->type_location, ['longue_duree', 'mixte']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky {{ in_array($chambre->type_location, ['mixte']) ? 'top-[400px]' : 'top-6' }}">
                    <div class="mb-4 flex flex-col justify-start">
                        <span class="text-2xl font-black text-gray-900">{{ number_format($chambre->loyer_mensuel ?? ($chambre->prix_base * 15), 0, ',', ' ') }} <span class="text-sm font-bold text-gray-500">FCFA</span></span>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">/ Mois (LONGUE DURÉE)</span>
                    </div>
                    
                    <div class="bg-red-50 text-red-800 p-3 rounded-lg mb-5 text-xs font-medium border border-red-100 flex items-start gap-2">
                        <i class="fas fa-info-circle mt-0.5 text-red-500"></i>
                        <span>Frais de visite : <strong class="font-black">{{ number_format($chambre->frais_visite ?? 5000, 0, ',', ' ') }} FCFA</strong> en ligne ou sur place.</span>
                    </div>

                    <!-- Visit Request Form -->
                    <form action="{{ route('chambres.demander-visite', $chambre->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @if ($errors->any())
                            <div class="bg-red-50 text-red-500 p-3 rounded-lg text-xs mb-3">
                                <ul class="list-disc pl-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="bg-green-50 text-green-600 p-3 rounded-lg text-xs font-medium mb-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Date souhaitée</label>
                            <input type="datetime-local" name="date_visite_souhaitee" required
                                min="{{ date('Y-m-d\TH:i', strtotime('+1 day')) }}"
                                class="w-full text-xs border border-gray-200 rounded-lg p-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-gray-50 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Téléphone / WhatsApp</label>
                            <input type="tel" name="telephone" required
                                value="{{ Auth::check() ? Auth::user()->telephone : old('telephone') }}"
                                class="w-full text-xs border border-gray-200 rounded-lg p-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-gray-50 transition-colors" placeholder="+228 99 00 00 00">
                        </div>

                        @auth
                            <button type="submit"
                                    class="w-full bg-gray-900 text-white font-bold uppercase tracking-wider text-[11px] px-4 py-3.5 rounded-xl hover:bg-gray-800 transition-colors shadow-md flex justify-center items-center gap-2">
                                <i class="fas fa-calendar-check"></i> Programmer la visite
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                                class="w-full bg-gray-900 text-white font-bold uppercase tracking-wider text-[11px] px-4 py-3.5 rounded-xl hover:bg-gray-800 transition-colors shadow-md flex justify-center items-center gap-2 text-center decoration-transparent">
                                <i class="fas fa-lock"></i> Se connecter pour visiter
                            </a>
                        @endauth
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Appartements similaires -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Appartements similaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($chambresSimilaires as $chambreSimilaire)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="h-48 relative">
                            @if($chambreSimilaire->medias->first())
                                <img src="{{ Storage::url($chambreSimilaire->medias->first()->chemin_fichier) }}" 
                                    alt="{{ $chambreSimilaire->typeChambre->nom }}"
                                    class="w-full h-full object-cover">
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $chambreSimilaire->typeChambre->nom }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-semibold text-lg text-gray-900">App. {{ $chambreSimilaire->numero_chambre }}</h3>
                                @if(in_array($chambreSimilaire->type_location, ['longue_duree']))
                                    <span class="text-[10px] bg-amber-50 text-amber-700 px-2 py-0.5 rounded border border-amber-100 font-bold tracking-wider">LONG SÉJOUR</span>
                                @elseif(in_array($chambreSimilaire->type_location, ['courte_duree']))
                                    <span class="text-[10px] bg-red-50 text-red-700 px-2 py-0.5 rounded border border-red-100 font-bold tracking-wider">COURT SÉJOUR</span>
                                @else
                                    <span class="text-[10px] bg-red-50 text-red-700 px-2 py-0.5 rounded border border-red-100 font-bold tracking-wider">MIXTE</span>
                                @endif
                            </div>
                            <div class="flex items-center mt-1 mb-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($chambreSimilaire->note_moyenne))
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @else
                                            <i class="far fa-star text-yellow-400"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-500 ml-1">({{ $chambreSimilaire->avis->count() }})</span>
                            </div>
                            <div class="flex justify-between items-end mt-4">
                                <div class="flex flex-col">
                                    <span class="font-black text-red-600">{{ number_format($chambreSimilaire->prix_base, 0, ',', ' ') }} F</span>
                                    <span class="text-[10px] text-gray-400 font-medium">Prix repère</span>
                                </div>
                                <a href="{{ route('chambres.show', $chambreSimilaire->id) }}" class="text-red-600 hover:text-red-800 text-xs font-bold uppercase tracking-wider">
                                    Voir l'appartement
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Galerie photos overlay -->
<div id="photo-gallery" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <button onclick="closeGallery()" class="absolute top-4 right-4 text-white text-xl">
        <i class="fas fa-times"></i>
    </button>
    <div class="w-full max-w-5xl">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($chambre->medias as $media)
                    <div class="swiper-slide">
                        <img src="{{ Storage::url($media->chemin_fichier) }}" alt="{{ $chambre->typeChambre->nom }}" class="max-h-[80vh] mx-auto">
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    // Initialize map
    function initMap() {
        const lat = {{ $chambre->propriete->latitude ?? 0 }};
        const lng = {{ $chambre->propriete->longitude ?? 0 }};
        
        const map = new google.maps.Map(document.getElementById('map'), {
            center: { lat, lng },
            zoom: 15
        });
        
        new google.maps.Marker({
            position: { lat, lng },
            map: map,
            title: 'H - Chambre {{ $chambre->numero_chambre }}'
        });
    }
    
    // Initialize photo gallery
    function openGallery() {
        document.getElementById('photo-gallery').classList.remove('hidden');
        
        new Swiper('.swiper-container', {
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    }
    
    function closeGallery() {
        document.getElementById('photo-gallery').classList.add('hidden');
    }
    
    // Toggle FAQ answers
    function toggleFaq(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector('i');
        
        answer.classList.toggle('hidden');
        
        if (answer.classList.contains('hidden')) {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        } else {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    }
    
    // Check availability
    document.getElementById('appartement.check_availability').addEventListener('click', function() {
        const arrival = document.getElementById('date_arrivee').value;
        const departure = document.getElementById('date_depart').value;
        
        if (!arrival || !departure) {
            window.toast.warning('Veuillez sélectionner les dates d\'arrivée et de départ');
            return;
        }
        
        // Appel AJAX pour vérifier la disponibilité
        fetch(`{{ route('chambres.check.availability', $chambre->id) }}?arrival=${arrival}&departure=${departure}`)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    window.toast.success('La chambre est disponible pour ces dates !');
                } else {
                    window.toast.error('Désolé, la chambre n\'est pas disponible pour ces dates.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.toast.error('Une erreur s\'est produite lors de la vérification de la disponibilité.');
            });
    });
</script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=VOTRE_CLE_API&callback=initMap" async defer></script>
@endsection
