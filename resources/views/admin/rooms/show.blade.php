@extends('layouts.playout')

@section('title', 'Détails de la chambre')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Détails de la chambre {{ $chambre->numero_chambre }}</h1>

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

    <!-- Fiche d'information -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><strong>Numéro:</strong> {{ $chambre->numero_chambre }}</p>
                <p><strong>Type:</strong> {{ $chambre->typeChambre->nom }}</p>
                <p><strong>Propriété:</strong> {{ $chambre->propriete->nom }}</p>
            </div>
            <div>
                <p><strong>Prix de base:</strong> {{ number_format($chambre->prix_base, 0, ',', ' ') }} XOF</p>
                <p><strong>Statut:</strong> 
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $chambre->statut == 'disponible' ? 'bg-green-100 text-green-800' : 
                           ($chambre->statut == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($chambre->statut) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Dates disponibles -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Dates disponibles ({{ $dateDebut->format('d/m/Y') }} - {{ $dateFin->format('d/m/Y') }})</h2>
        @if (count($datesDisponibles) > 0)
            <div class="grid grid-cols-3 gap-4">
                @foreach ($datesDisponibles as $date)
                    <div class="bg-green-50 p-2 rounded text-center">
                        {{ $date }}
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">Aucune date disponible dans cette période.</p>
        @endif
    </div>

    <!-- Changer le statut -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Changer le statut</h2>
        <form method="POST" action="{{ route('admin.rooms.updateStatus', $chambre->id) }}">
            @csrf
            @method('PATCH')
            <div class="flex items-center gap-4">
                <select name="statut" class="block w-48 p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="disponible" {{ $chambre->statut == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="maintenance" {{ $chambre->statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="indisponible" {{ $chambre->statut == 'indisponible' ? 'selected' : '' }}>Indisponible</option>
                </select>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

    <!-- Ajouter période de maintenance -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Ajouter période de maintenance</h2>
        <form method="POST" action="{{ route('admin.rooms.createMaintenance', $chambre->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                    <input type="date" name="date_debut" id="date_debut" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                    <input type="date" name="date_fin" id="date_fin" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="raison" class="block text-sm font-medium text-gray-700">Raison</label>
                    <input type="text" name="raison" id="raison" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Ajouter
                </button>
            </div>
        </form>
    </div>

    <!-- Périodes de maintenance -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Périodes de maintenance</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date début</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raison</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($periodesIndisponibilite as $periode)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $periode->date_debut->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $periode->date_fin->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $periode->raison }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.rooms.deleteMaintenance', [$chambre->id, $periode->id]) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette période de maintenance ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Réservations -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Réservations</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date arrivée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date départ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($reservations as $reservation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $reservation->client && $reservation->client->user ? $reservation->client->user->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->date_arrivee->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->date_depart->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($reservation->prix_total, 0, ',', ' ') }} XOF</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $reservation->statut == 'terminee' ? 'bg-green-100 text-green-800' : 
                                       ($reservation->statut == 'en_attente_paiement' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($reservation->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.reservations.show', $reservation->id) }}"
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