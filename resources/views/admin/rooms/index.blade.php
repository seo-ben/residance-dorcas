@extends('layouts.playout')

@section('title', 'Gestion des appartement')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Gestion des appartement</h1>

    <!-- Messages de succès ou d'erreur -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Total des appartement</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $statistiques['total_appartement'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">appartement occupées</h3>
            <p class="text-2xl font-bold text-red-600">{{ $statistiques['appartement_occupees'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">En maintenance</h3>
            <p class="text-2xl font-bold text-yellow-600">{{ $statistiques['appartement_en_maintenance'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">appartement disponibles</h3>
            <p class="text-2xl font-bold text-green-600">{{ $statistiques['appartement_disponibles'] }}</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Filtres</h2>
        <form method="GET" action="{{ route('admin.rooms.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                <input type="date" name="date_debut" id="date_debut" value="{{ $dateDebut }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin" value="{{ $dateFin }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" id="statut"
                        class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="all" {{ $statut == 'all' ? 'selected' : '' }}>Tous</option>
                    <option value="disponible" {{ $statut == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="maintenance" {{ $statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="indisponible" {{ $statut == 'indisponible' ? 'selected' : '' }}>Indisponible</option>
                </select>
            </div>
            <div class="md:col-span-3 flex justify-end mt-4">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des appartement -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold">Liste des appartement</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriété</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Occupation actuelle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($appartement as $chambre)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $chambre->numero_chambre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $chambre->typeChambre->nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $chambre->propriete->nom }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $chambre->statut == 'disponible' ? 'bg-green-100 text-green-800' : 
                                       ($chambre->statut == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($chambre->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $isOccupied = $chambre->detailsReservations->filter(function($detail) {
                                        return $detail->reservation && 
                                               $detail->reservation->date_arrivee <= now() && 
                                               $detail->reservation->date_depart >= now() && 
                                               $detail->reservation->statut != 'annulee';
                                    })->count() > 0;
                                    $isInMaintenance = $chambre->statut == 'maintenance' && $chambre->periodesIndisponibilite->filter(function($periode) {
                                        return $periode->date_debut <= now() && $periode->date_fin >= now();
                                    })->count() > 0;
                                @endphp
                                @if ($isOccupied)
                                    Occupée
                                @elseif ($isInMaintenance)
                                    En maintenance
                                @else
                                    Libre
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.rooms.show', $chambre->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900">Détails</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection