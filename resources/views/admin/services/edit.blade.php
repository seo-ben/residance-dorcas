@extends('layouts.playout')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-8">
        <div class="flex items-center mb-8">
            <a href="{{ route('admin.services.index') }}" class="mr-4 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Modifier le service : {{ $service->nom }}</h1>
        </div>

        <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom du service*</label>
                    <input type="text" name="nom" value="{{ $service->nom }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix (FCFA)*</label>
                    <input type="number" name="prix" value="{{ $service->prix }}" required min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ $service->description }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Disponibilité</label>
                    <select name="disponibilite" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="24h" {{ $service->disponibilite == '24h' ? 'selected' : '' }}>24h/24</option>
                        <option value="jour" {{ $service->disponibilite == 'jour' ? 'selected' : '' }}>En journée</option>
                        <option value="horaires_specifiques" {{ $service->disponibilite == 'horaires_specifiques' ? 'selected' : '' }}>Horaires spécifiques</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="actif" {{ $service->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ $service->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>

                <div class="horaires {{ $service->disponibilite == 'horaires_specifiques' ? '' : 'hidden' }} grid grid-cols-2 gap-4 col-span-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Début (Heure)</label>
                        <input type="time" name="horaires_debut" value="{{ $service->horaires_debut }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fin (Heure)</label>
                        <input type="time" name="horaires_fin" value="{{ $service->horaires_fin }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-md font-bold hover:bg-red-700 transition duration-300">
                    Mettre à jour le service
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const dispoSelect = document.querySelector('select[name="disponibilite"]');
    const horairesDiv = document.querySelector('.horaires');
    
    dispoSelect.addEventListener('change', function() {
        if (this.value === 'horaires_specifiques') {
            horairesDiv.classList.remove('hidden');
        } else {
            horairesDiv.classList.add('hidden');
        }
    });
</script>
@endsection
