@extends('layouts.playout')

@section('title', 'Gestion des Locations de Véhicules')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Locations de Véhicules</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.vehicules.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition duration-300 flex items-center text-sm font-medium">
                <i class="fas fa-car mr-2"></i>
                Voir le Parc
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Période</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Véhicule</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prix Total</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($rentals as $rental)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">Du {{ $rental->date_debut->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">Au {{ $rental->date_fin->format('d/m/Y') }}</div>
                            <div class="text-[10px] uppercase font-black text-primary-600 mt-1">
                                {{ $rental->date_debut->diffInDays($rental->date_fin) }} Jours
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-16 bg-gray-100 rounded-lg overflow-hidden mr-3">
                                    @if($rental->vehicule->primaryImage)
                                        <img src="{{ asset('storage/' . $rental->vehicule->primaryImage->chemin_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-car text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $rental->vehicule->marque }} {{ $rental->vehicule->modele }}</div>
                                    <div class="text-xs text-gray-500">{{ $rental->vehicule->immatriculation }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $rental->client->user->name ?? 'Client inconnu' }}</div>
                            <div class="text-xs text-gray-500">{{ $rental->client->user->email ?? '' }}</div>
                            <div class="text-xs text-gray-500">{{ $rental->client->user->telephone ?? '' }}</div>
                            @if($rental->id_reservation)
                                <a href="{{ route('admin.reservations.show', $rental->id_reservation) }}" class="inline-flex items-center text-[10px] font-bold text-blue-600 mt-1 hover:underline">
                                    <i class="fas fa-link mr-1"></i> Réservation #{{ $rental->reservation->reference }}
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-black text-gray-900">{{ number_format($rental->prix_total, 0, ',', ' ') }} FCFA</div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $rental->statut_paiement == 'paye' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ str_replace('_', ' ', $rental->statut_paiement) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                {{ $rental->statut == 'terminee' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $rental->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $rental->statut == 'confirmee' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $rental->statut == 'en_cours' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                {{ $rental->statut == 'annulee' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucfirst(str_replace('_', ' ', $rental->statut)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-primary-600 hover:text-primary-900 font-bold flex items-center justify-end w-full">
                                Gérer
                                <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                class="absolute right-0 mt-2 w-64 rounded-2xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden text-left border border-gray-100">
                                <form action="{{ route('admin.vehicules.update-rental-status', $rental) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-3 border-b border-gray-50 bg-gray-50/50">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Statut Location</p>
                                        <div class="grid grid-cols-2 gap-1">
                                            <button type="submit" name="statut" value="confirmee" class="px-2 py-1.5 text-left text-[11px] font-bold hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">Confirmer</button>
                                            <button type="submit" name="statut" value="en_cours" class="px-2 py-1.5 text-left text-[11px] font-bold hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors">En cours</button>
                                            <button type="submit" name="statut" value="terminee" class="px-2 py-1.5 text-left text-[11px] font-bold hover:bg-green-50 hover:text-green-600 rounded-lg transition-colors">Terminer</button>
                                            <button type="submit" name="statut" value="annulee" class="px-2 py-1.5 text-left text-[11px] font-bold hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors text-red-500">Annuler</button>
                                        </div>
                                    </div>

                                    <div class="p-3 border-b border-gray-50">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Paiement</p>
                                        <select name="statut_paiement" class="w-full text-xs p-2 rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500">
                                            <option value="non_paye" {{ $rental->statut_paiement == 'non_paye' ? 'selected' : '' }}>Non payé</option>
                                            <option value="partiel" {{ $rental->statut_paiement == 'partiel' ? 'selected' : '' }}>Partiel</option>
                                            <option value="paye" {{ $rental->statut_paiement == 'paye' ? 'selected' : '' }}>Payé</option>
                                            <option value="rembourse" {{ $rental->statut_paiement == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                                        </select>
                                    </div>

                                    <div class="p-3 bg-gray-50/30">
                                        <textarea name="notes" placeholder="Note interne..." class="w-full text-xs p-2 rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500 min-h-[60px]">{{ $rental->notes }}</textarea>
                                        <button type="submit" class="mt-2 w-full bg-primary-600 text-white text-[11px] font-bold py-2 rounded-xl hover:bg-primary-700 transition-colors shadow-lg shadow-primary-100">
                                            Mettre à jour
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-calendar-times text-gray-200 text-5xl mb-4"></i>
                                <p class="text-gray-400 font-medium">Aucune location enregistrée pour le moment.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $rentals->links() }}
    </div>
</div>
@endsection
