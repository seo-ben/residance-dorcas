@extends('layouts.playout')

@section('title', 'Modifier un équipement')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .form-container {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }
    
    .icon-preview {
        font-size: 1.5rem;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f0f4f8;
        margin-right: 1rem;
    }
    
    .select2-container--default .select2-selection--single {
        height: 42px;
        border-color: #e2e8f0;
        border-radius: 0.375rem;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
        padding-left: 12px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #4f46e5;
    }
    
    .page-header {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 1rem 1rem;
    }
    
    .form-input-group {
        margin-bottom: 1.5rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
    }
    
    .icon-result-option {
        display: flex;
        align-items: center;
        padding: 8px;
    }
    
    .icon-option-preview {
        margin-right: 10px;
        width: 24px;
        text-align: center;
    }
</style>

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="form-container p-8">
            <div class="flex items-center mb-8">
                <div class="bg-indigo-100 p-3 rounded-full text-indigo-600">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 ml-4">
                    {{ __('Modifier un équipement') }}
                </h2>
            </div>
            
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Veuillez corriger les erreurs suivantes :</p>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('admin.equipements.update', $equipement) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-input-group">
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'équipement</label>
                        <input id="nom" type="text" name="nom" value="{{ old('nom', $equipement->nom) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="Ex: Climatisation">
                    </div>
                    
                    <div class="form-input-group">
                        <label for="icone" class="block text-sm font-medium text-gray-700 mb-1">Icône</label>
                        <div class="flex items-center">
                            <div id="icon-preview" class="icon-preview">
                                <i class="fas fa-{{ $equipement->icone ?? 'question' }}"></i>
                            </div>
                            <div class="flex-grow">
                                <select id="icone" name="icone" class="icon-select block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Sélectionnez une icône</option>
                                </select>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Choisissez l'icône représentant votre équipement</p>
                    </div>
                </div>
                
                <div class="form-input-group mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                              placeholder="Décrivez cet équipement et ses caractéristiques...">{{ old('description', $equipement->description) }}</textarea>
                </div>
                
                <div class="flex items-center justify-end mt-8 pt-5 border-t border-gray-200">
                    <a href="{{ route('admin.equipements.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-1"></i> Retour
                    </a>
                    <button type="submit" class="ml-4 bg-gray btn-primary inline-flex items-center px-5 py-2.5 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i> {{ __('Enregistrer les modifications') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier que jQuery est chargé
    if (typeof jQuery === 'undefined') {
        console.error('jQuery n\'est pas chargé !');
        return;
    }
    
    // Vérifier que Select2 est chargé
    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 n\'est pas chargé !');
        return;
    }
    
    // Liste des icônes pertinentes pour un hôtel
    const hotelIcons = [
        {id: 'bed', text: 'Lit (bed)', icon: 'bed'},
        {id: 'shower', text: 'Douche (shower)', icon: 'shower'},
        {id: 'bath', text: 'Baignoire (bath)', icon: 'bath'},
        {id: 'wifi', text: 'WiFi (wifi)', icon: 'wifi'},
        {id: 'coffee', text: 'Café (coffee)', icon: 'coffee'},
        {id: 'utensils', text: 'Couverts (utensils)', icon: 'utensils'},
        {id: 'snowflake', text: 'Climatisation (snowflake)', icon: 'snowflake'},
        {id: 'tv', text: 'Télévision (tv)', icon: 'tv'},
        {id: 'swimming-pool', text: 'Piscine (swimming-pool)', icon: 'swimming-pool'},
        {id: 'dumbbell', text: 'Salle de sport (dumbbell)', icon: 'dumbbell'},
        {id: 'spa', text: 'Spa (spa)', icon: 'spa'},
        {id: 'concierge-bell', text: 'Service (concierge-bell)', icon: 'concierge-bell'},
        {id: 'parking', text: 'Parking (parking)', icon: 'parking'},
        {id: 'glass-martini-alt', text: 'Bar (glass-martini-alt)', icon: 'glass-martini-alt'},
        {id: 'wheelchair', text: 'Accessibilité (wheelchair)', icon: 'wheelchair'},
        {id: 'baby', text: 'Bébé (baby)', icon: 'baby'},
        {id: 'paw', text: 'Animaux (paw)', icon: 'paw'},
        {id: 'smoking-ban', text: 'Non-fumeur (smoking-ban)', icon: 'smoking-ban'},
        {id: 'lock', text: 'Coffre-fort (lock)', icon: 'lock'},
        {id: 'phone', text: 'Téléphone (phone)', icon: 'phone'},
        {id: 'air-freshener', text: 'Air purifié (air-freshener)', icon: 'air-freshener'},
        {id: 'fan', text: 'Ventilateur (fan)', icon: 'fan'},
        {id: 'hot-tub', text: 'Jacuzzi (hot-tub)', icon: 'hot-tub'},
        {id: 'umbrella-beach', text: 'Plage (umbrella-beach)', icon: 'umbrella-beach'},
        {id: 'shuttle-van', text: 'Navette (shuttle-van)', icon: 'shuttle-van'},
        {id: 'luggage-cart', text: 'Bagagerie (luggage-cart)', icon: 'luggage-cart'},
        {id: 'door-open', text: 'Entrée (door-open)', icon: 'door-open'},
        {id: 'calendar-check', text: 'Réservation (calendar-check)', icon: 'calendar-check'},
        {id: 'key', text: 'Clé (key)', icon: 'key'},
        {id: 'child', text: 'Enfant (child)', icon: 'child'},
        {id: 'cocktail', text: 'Cocktail (cocktail)', icon: 'cocktail'},
        {id: 'cookie', text: 'Petit-déjeuner (cookie)', icon: 'cookie'},
        {id: 'desktop', text: 'Ordinateur (desktop)', icon: 'desktop'},
        {id: 'dice', text: 'Jeux (dice)', icon: 'dice'},
        {id: 'door-closed', text: 'Porte fermée (door-closed)', icon: 'door-closed'},
        {id: 'elevator', text: 'Ascenseur (elevator)', icon: 'elevator'},
        {id: 'newspaper', text: 'Journaux (newspaper)', icon: 'newspaper'}
    ];

    // Initialisation de Select2 avec jQuery
    $(document).ready(function() {
        $('#icone').select2({
            templateResult: formatIconOption,
            templateSelection: formatIconSelection,
            escapeMarkup: function(m) { return m; },
            data: hotelIcons
        });

        // Formatage des options dans le dropdown
        function formatIconOption(icon) {
            if (!icon.id) {
                return icon.text;
            }
            return $('<div class="icon-result-option"><span class="icon-option-preview"><i class="fas fa-' + icon.icon + '"></i></span> ' + icon.text + '</div>');
        }

        // Formatage de l'élément sélectionné
        function formatIconSelection(icon) {
            if (!icon.id) {
                return icon.text;
            }
            return $('<span><i class="fas fa-' + icon.icon + '"></i> ' + icon.text + '</span>');
        }

        // Définir la valeur initiale du select
        const currentIcon = '{{ $equipement->icone }}';
        if (currentIcon) {
            // Trouver l'option correspondante
            const iconOption = hotelIcons.find(icon => icon.id === currentIcon);
            
            if (iconOption) {
                // Créer une nouvelle option
                const newOption = new Option(iconOption.text, iconOption.id, true, true);
                // Ajouter au select et déclencher change
                $('#icone').append(newOption).trigger('change');
            }
        }

        // Mise à jour de l'aperçu de l'icône
        $('#icone').on('change', function() {
            const iconValue = $(this).val();
            if (iconValue) {
                $('#icon-preview').html('<i class="fas fa-' + iconValue + '"></i>');
            } else {
                $('#icon-preview').html('<i class="fas fa-question"></i>');
            }
        });
        
        // Afficher l'état de la sélection dans la console pour le débogage
        console.log('Select2 initialisé avec succès');
    });
});
</script>

@endsection
