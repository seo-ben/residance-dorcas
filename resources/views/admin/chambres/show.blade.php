@extends('layouts.playout')

@section('title', 'Détails de la Chambre')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Chambre') }} {{ $chambre->numero_chambre }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.chambres.edit', $chambre) }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('admin.chambres.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                    Retour à la liste
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Image Gallery -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Images</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse($chambre->medias as $media)
                        <div class="relative group" data-image-id="{{ $media->id }}">
                            <img src="{{ Storage::url($media->chemin_fichier) }}" 
                                 alt="Image de la chambre" 
                                 class="w-full h-64 object-cover rounded-lg">
                            @if($media->est_couverture)
                                <div class="absolute top-2 right-2 bg-indigo-600 text-white px-2 py-1 rounded text-xs">
                                    Image principale
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                <button onclick="deleteImage({{ $media->id }})" 
                                        class="hidden group-hover:block bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition">
                                    <i class="fas fa-trash mr-1"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 bg-gray-100 rounded-lg p-4 text-center text-gray-500">
                            Aucune image disponible
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Informations générales -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Numéro de chambre</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $chambre->numero_chambre }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Type de chambre</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $chambre->typeChambre->nom }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Étage</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $chambre->etage }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Prix de base</p>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($chambre->prix_base, 2) }} F CFA</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Propriété</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $chambre->propriete->nom }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Statut</p>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $chambre->statut === 'disponible' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $chambre->statut === 'occupee' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $chambre->statut === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $chambre->statut === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($chambre->statut) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Équipements -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Équipements</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($chambre->equipements as $equipement)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-100 rounded-full">
                                <i class="fas fa-{{ $equipement->icone ?? 'cube' }} text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $equipement->nom }}</p>
                                <p class="text-sm text-gray-500">
                                    Quantité: {{ $equipement->pivot->quantite }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 bg-gray-100 rounded-lg p-4 text-center text-gray-500">
                            Aucun équipement disponible
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Notes -->
            @if($chambre->notes)
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $chambre->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Delete Button -->
        <div class="mt-6 flex justify-end">
            <form action="{{ route('admin.chambres.destroy', $chambre) }}" method="POST" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette chambre ?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                    Supprimer la chambre
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteImage(imageId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        fetch(`/admin/appartement/media/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const imageContainer = document.querySelector(`[data-image-id="${imageId}"]`);
                if (imageContainer) {
                    imageContainer.remove();
                    
                    const successMessage = document.createElement('div');
                    successMessage.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                    successMessage.textContent = 'Image supprimée avec succès';
                    document.querySelector('.max-w-7xl').insertBefore(successMessage, document.querySelector('.bg-white.shadow'));
                    
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);

                    const remainingImages = document.querySelectorAll('[data-image-id]');
                    if (remainingImages.length === 0) {
                        const gallery = document.querySelector('.grid');
                        gallery.innerHTML = `
                            <div class="col-span-3 bg-gray-100 rounded-lg p-4 text-center text-gray-500">
                                Aucune image disponible
                            </div>
                        `;
                    }
                }
            } else {
                throw new Error(data.message || 'Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMessage = document.createElement('div');
            errorMessage.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
            errorMessage.textContent = error.message || 'Une erreur est survenue lors de la suppression de l\'image';
            document.querySelector('.max-w-7xl').insertBefore(errorMessage, document.querySelector('.bg-white.shadow'));
            
            setTimeout(() => {
                errorMessage.remove();
            }, 3000);
        });
    }
}
</script>
@endpush
@endsection