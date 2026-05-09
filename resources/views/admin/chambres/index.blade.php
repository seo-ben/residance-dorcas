@extends('layouts.playout')

@section('title', 'Gestion des Appartements')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Appartements</h1>
            <p class="text-gray-500 font-medium">Gérez le parc immobilier et les disponibilités de vos hébergements</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.chambres.create') }}" class="bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-red-200 hover:bg-red-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Nouvel Appartement
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alerts')

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Dispo -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-door-open text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Disponibles</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black text-gray-900">{{ $stats['disponibles']['count'] }}</h3>
                    <span class="text-xs font-bold text-green-600 bg-green-50 px-1.5 py-0.5 rounded">{{ $stats['disponibles']['percentage'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Occupés -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-check text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Occupés</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black text-gray-900">{{ $stats['occupees']['count'] }}</h3>
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded">{{ $stats['occupees']['percentage'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-tools text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Maintenance</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black text-gray-900">{{ $stats['maintenance']['count'] }}</h3>
                    <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-1.5 py-0.5 rounded">{{ $stats['maintenance']['percentage'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Taux -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Taux d'occup.</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['taux_occupation'] }}%</h3>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="search" placeholder="Rechercher par numéro ou nom..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-500/20 transition-all">
            </div>
            <div class="w-full md:w-48">
                <select id="type" class="w-full bg-gray-50 border-none rounded-xl text-sm py-2.5 focus:ring-2 focus:ring-red-500/20">
                    <option value="">Tous les types</option>
                    @foreach($typesappartement as $type)
                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-48">
                <select id="statut" class="w-full bg-gray-50 border-none rounded-xl text-sm py-2.5 focus:ring-2 focus:ring-red-500/20">
                    <option value="">Tous les statuts</option>
                    <option value="disponible">Disponible</option>
                    <option value="occupee">Occupée</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Hébergement</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Propriété / Étage</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Équipements</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Prix de base</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($appartement as $chambre)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 shadow-sm border border-gray-100">
                                    @if($chambre->medias->first())
                                        <img src="{{ Storage::url($chambre->medias->first()->chemin_fichier) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-gray-300"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">Appartement {{ $chambre->numero_chambre }}</div>
                                    <div class="text-xs font-medium text-gray-500">{{ $chambre->typeChambre->nom }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-800">{{ $chambre->propriete->nom ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">Étage : {{ $chambre->etage ?? '0' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center -space-x-1">
                                @foreach($chambre->equipements->take(3) as $equip)
                                    <div class="w-7 h-7 rounded-full bg-white border border-gray-100 flex items-center justify-center shadow-sm" title="{{ $equip->nom }}">
                                        <i class="{{ $equip->icone }} text-[10px] text-gray-600"></i>
                                    </div>
                                @endforeach
                                @if($chambre->equipements->count() > 3)
                                    <div class="w-7 h-7 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center shadow-sm text-[10px] font-bold text-gray-400">
                                        +{{ $chambre->equipements->count() - 3 }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-black text-gray-900">{{ number_format($chambre->prix_base, 0, ',', ' ') }} F</div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase">Par nuit</div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyles = [
                                    'disponible' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'icon' => 'fa-check-circle'],
                                    'occupee' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'icon' => 'fa-user-lock'],
                                    'maintenance' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'icon' => 'fa-tools'],
                                    'inactive' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'fa-times-circle'],
                                ];
                                $style = $statusStyles[$chambre->statut] ?? $statusStyles['inactive'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $style['bg'] }} {{ $style['text'] }}">
                                <i class="fas {{ $style['icon'] }}"></i>
                                {{ ucfirst($chambre->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('admin.chambres.show', $chambre) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm border border-gray-50" title="Voir">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.chambres.edit', $chambre) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all shadow-sm border border-gray-50" title="Modifier">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.chambres.destroy', $chambre) }}" method="POST" class="inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all shadow-sm border border-gray-50" title="Supprimer">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-door-closed text-4xl mb-4 opacity-20"></i>
                                <p>Aucun appartement trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($appartement->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $appartement->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const typeSelect = document.getElementById('type');
    const statutSelect = document.getElementById('statut');
    const tableRows = document.querySelectorAll('tbody tr:not(.empty-state-row)');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            if (row.cells.length < 5) return;
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
});
</script>
@endsection
