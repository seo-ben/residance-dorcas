@extends('layouts.playout')

@section('title', 'Gestion des Propriétés')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Propriétés</h1>
            <p class="text-gray-500 font-medium">Gérez vos résidences, hôtels et établissements</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.proprietes.create') }}" class="bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-red-200 hover:bg-red-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Nouvelle Propriété
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alerts')

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-building text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Actives</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $proprietes->where('statut', 'actif')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-building-circle-exclamation text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Inactives</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $proprietes->where('statut', '!=', 'actif')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-city text-indigo-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $proprietes->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Filtres & Recherche -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[300px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Rechercher une propriété par nom, ville..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-500/20 transition-all">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Propriété</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Localisation</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($proprietes as $propriete)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-14 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 shadow-sm border border-gray-100">
                                    @if($propriete->medias->first())
                                        <img src="{{ Storage::url($propriete->medias->first()->chemin_fichier) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-building text-gray-300"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $propriete->nom }}</div>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for($i=0; $i<$propriete->etoiles; $i++)
                                            <i class="fas fa-star text-[10px] text-yellow-400"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-800">{{ $propriete->ville }}</div>
                            <div class="text-xs text-gray-500">{{ $propriete->pays }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-700">{{ $propriete->telephone }}</div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $propriete->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($propriete->statut === 'actif')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700">
                                    <i class="fas fa-check-circle"></i> Actif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700">
                                    <i class="fas fa-times-circle"></i> Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('admin.proprietes.show', $propriete) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all border border-gray-50" title="Voir">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.proprietes.edit', $propriete) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all border border-gray-50" title="Modifier">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.proprietes.destroy', $propriete) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all border border-gray-50" title="Supprimer">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-hotel text-4xl mb-4 opacity-20"></i>
                                <p>Aucune propriété trouvée</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        tableRows.forEach(row => {
            if (row.cells.length < 4) return;
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
});
</script>
@endsection