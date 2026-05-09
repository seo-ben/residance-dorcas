@extends('layouts.plaout')
@section('title', 'Détails de la demande de visite')
@section('content')

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Header Premium -->
        <div class="text-center mb-16 animate-fade-in-up">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r  rounded-2xl mb-6">
                {{-- <i class="fas fa-calendar-check text-white text-2xl"></i> --}}
            </div>
            <h1 class="text-5xl font-black text-gray-900 mb-4 tracking-tight">Détails de la demande</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">Consultez les informations complètes de votre demande de visite</p>
            <div class="w-24 h-1 bg-gray-900 mx-auto mt-6 rounded-full"></div>
        </div>

        <!-- Cards Premium Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-16 animate-fade-in-up" style="animation-delay: 0.1s;">
            <!-- Card Informations Chambre -->
            <div class="xl:col-span-1 group">
                <div class="bg-white rounded-3xl p-8 transition-all duration-500 border border-gray-100 h-full">
                    <div class="flex items-center mb-8">
                        <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-bed text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Informations</h3>
                            <p class="text-gray-500">Chambre & Détails</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="group/item hover:bg-gray-50 -mx-2 px-2 py-3 rounded-xl transition-colors">
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Type de chambre</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $demande->chambre->typeChambre->nom }}</div>
                        </div>
                        
                        <div class="group/item hover:bg-gray-50 -mx-2 px-2 py-3 rounded-xl transition-colors">
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Numéro</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $demande->chambre->numero_chambre }}</div>
                        </div>
                        
                        <div class="group/item hover:bg-gray-50 -mx-2 px-2 py-3 rounded-xl transition-colors">
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Date demande</div>
                            <div class="text-2xl font-bold text-gray-900">
                                {{ is_string($demande->date_demande) ? 
                                    \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') : 
                                    $demande->date_demande->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Statut & Planning -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-3xl p-8 transition-all duration-500 border border-gray-100 h-full">
                    <div class="flex items-center mb-8">
                        <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Planning & Statut</h3>
                            <p class="text-gray-500">Date & État de la demande</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="group/item hover:bg-gray-50 -mx-2 px-2 py-4 rounded-xl transition-colors">
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Date souhaitée</div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ is_string($demande->date_visite_souhaitee) ? 
                                        \Carbon\Carbon::parse($demande->date_visite_souhaitee)->format('d/m/Y') : 
                                        $demande->date_visite_souhaitee->format('d/m/Y') }}
                                </div>
                            </div>
                            
                            <div class="group/item hover:bg-gray-50 -mx-2 px-2 py-4 rounded-xl transition-colors">
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Statut actuel</div>
                                @if($demande->statut == 'en_attente')
                                    <div class="inline-flex items-center px-6 py-3 bg-red-50 text-red-800 rounded-2xl border-2 border-red-200 font-bold text-lg">
                                        <div class="w-3 h-3 bg-red-400 rounded-full mr-3 animate-pulse"></div>
                                        En attente
                                    </div>
                                @elseif($demande->statut == 'confirmee')
                                    <div class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-2xl border-2 border-red-700 font-bold text-lg">
                                        <div class="w-3 h-3 bg-white rounded-full mr-3"></div>
                                        Confirmée
                                    </div>
                                @elseif($demande->statut == 'annulee')
                                    <div class="inline-flex items-center px-6 py-3 bg-red-50 text-red-800 rounded-2xl border-2 border-red-200 font-bold text-lg">
                                        <div class="w-3 h-3 bg-red-400 rounded-full mr-3"></div>
                                        Annulée
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            @if($demande->statut == 'confirmee' && $demande->date_confirmation)
                                <div class="bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-200 rounded-2xl p-6">
                                    <div class="flex items-center mb-3">
                                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </div>
                                        <div class="text-red-800 font-bold">Visite Confirmée</div>
                                    </div>
                                    <div class="text-red-700 text-lg font-semibold">
                                        {{ \Carbon\Carbon::parse($demande->date_confirmation)->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-6">
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Message</div>
                                <div class="bg-gray-50 border-2 border-gray-200 rounded-2xl p-6 text-gray-700 leading-relaxed italic">
                                    {{ $demande->message ?: 'Aucun message spécifique' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Galerie Premium -->
        <div class="bg-white rounded-3xl p-10 mb-16 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-gray-900 to-gray-700 rounded-2xl flex items-center justify-center mr-6">
                        <i class="fas fa-images text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-900">Galerie Photos</h2>
                        <p class="text-gray-500 text-lg">Découvrez votre future chambre</p>
                    </div>
                </div>
                <div class="hidden md:block text-gray-400">
                    <i class="fas fa-grip-horizontal text-2xl"></i>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($demande->chambre->medias as $media)
                    <div class="group relative aspect-[4/3] rounded-2xl overflow-hidden cursor-pointer bg-gray-100" onclick="openModal('{{ Storage::url($media->chemin_fichier) }}')">
                        <img src="{{ Storage::url($media->chemin_fichier) }}" alt="Photo de la chambre" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold">Voir en grand</div>
                                    <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                        <i class="fas fa-expand text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 w-10 h-10 bg-black/20 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 scale-75 group-hover:scale-100">
                            <i class="fas fa-search-plus text-white text-sm"></i>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                        <div class="w-24 h-24 bg-gray-100 rounded-3xl flex items-center justify-center mb-6">
                            <i class="fas fa-image text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">Aucune photo disponible</h3>
                        <p class="text-gray-500">Les photos de cette chambre seront ajoutées prochainement</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Section Réservation Premium -->
        @if($reservation)
            <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-black rounded-3xl p-10  animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center mb-10">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-6">
                        <i class="fas fa-bookmark text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black mb-2">Réservation Associée</h2>
                        <p class="text-gray-300 text-lg">Votre réservation créée suite à cette visite</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
                        <div class="text-xs font-bold text-gray-300 uppercase tracking-widest mb-2">Référence</div>
                        <div class="text-2xl font-black">{{ $reservation->reference }}</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
                        <div class="text-xs font-bold text-gray-300 uppercase tracking-widest mb-2">Arrivée</div>
                        <div class="text-2xl font-black">{{ $reservation->date_arrivee->format('d/m/Y') }}</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
                        <div class="text-xs font-bold text-gray-300 uppercase tracking-widest mb-2">Départ</div>
                        <div class="text-2xl font-black">{{ $reservation->date_depart->format('d/m/Y') }}</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
                        <div class="text-xs font-bold text-gray-300 uppercase tracking-widest mb-2">Total</div>
                        <div class="text-2xl font-black">{{ number_format($reservation->montant_total, 0, ',', ' ') }} <span class="text-lg">FCFA</span></div>
                    </div>
                </div>
                
                <div class="text-center">
                    @if(in_array($reservation->statut, ['brouillon', 'en_attente_paiement']))
                        <div class="mb-6">
                            @if($reservation->statut == 'brouillon')
                                <div class="inline-flex items-center px-6 py-3 bg-red-500/20 text-red-200 rounded-2xl border border-red-500/30 font-bold">
                                    <i class="fas fa-edit mr-3"></i>Réservation en brouillon
                                </div>
                            @else
                                <div class="inline-flex items-center px-6 py-3 bg-red-500/20 text-red-200 rounded-2xl border border-red-500/30 font-bold">
                                    <i class="fas fa-credit-card mr-3"></i>En attente de paiement
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('reservations.continue', $reservation->id) }}" class="inline-flex items-center px-10 py-4 bg-white text-gray-900 font-black rounded-2xl hover:bg-gray-100 transition-all duration-300 text-lg shadow-lg hover:shadow-xl hover:scale-105">
                            <i class="fas fa-arrow-right mr-3"></i>
                            Continuer la réservation
                        </a>
                    @elseif($reservation->statut == 'confirmee')
                        <div class="inline-flex items-center px-8 py-4 bg-red-500/20 text-red-200 rounded-2xl border-2 border-red-500/30 font-bold text-lg">
                            <i class="fas fa-check-circle mr-3"></i>Réservation confirmée
                        </div>
                    @elseif($reservation->statut == 'annulee')
                        <div class="inline-flex items-center px-8 py-4 bg-red-500/20 text-red-200 rounded-2xl border-2 border-red-500/30 font-bold text-lg">
                            <i class="fas fa-times-circle mr-3"></i>Réservation annulée
                        </div>
                    @endif
                </div>
            </div>
        
        @endif
    </div>
</div>

<!-- Modal Premium -->
<div id="imageModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-6">
    <div class="relative max-w-6xl max-h-full">
        <button class="absolute -top-4 -right-4 w-12 h-12 bg-white/10 backdrop-blur-sm text-white rounded-full flex items-center justify-center hover:bg-white/20 transition-all z-10" onclick="closeModal()">
            <i class="fas fa-times text-xl"></i>
        </button>
        <img id="modalImage" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl">
    </div>
</div>

<script>
// Fonction pour ouvrir le modal
function openModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modal.classList.remove('hidden');
    modalImage.src = imageSrc;
    document.body.style.overflow = 'hidden';
}

// Fonction pour fermer le modal
function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Fermer avec Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Animations au chargement
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.animate-fade-in-up');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 150);
    });
});
</script>

@endsection