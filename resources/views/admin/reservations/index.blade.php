@extends('layouts.playout')

@section('title', 'Réservations Admin')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Gestion des Réservations</h1>
                <p class="text-gray-500 font-medium">Visualisez et gérez l'ensemble de vos séjours clients</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reservations.export', request()->query()) }}" class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm hover:shadow transition-all flex items-center gap-2">
                    <i class="fas fa-download text-red-500"></i> Exporter
                </a>
                <a href="{{ route('admin.reservations.create') }}" class="bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-red-200 hover:bg-red-700 transition-all flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nouvelle Réservation
                </a>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-sign-in-alt text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-900">{{ $stats['arrivees_aujourdhui'] }}</div>
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Arrivées (Aujourd'hui)</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-900">{{ $stats['departs_aujourdhui'] }}</div>
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Départs (Aujourd'hui)</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-900">{{ $stats['paiements_en_attente'] }}</div>
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Paiements en attente</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4 border-l-4 border-l-red-500">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-gray-900">{{ $stats['total_actifs'] }}</div>
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Réservations Actives</div>
                </div>
            </div>
        </div>

        <!-- Filter and Search Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-8 overflow-hidden">
            <!-- Status Tabs -->
            <div class="flex border-b border-gray-100 p-1 bg-gray-50/50">
                @php $currentStatut = request('statut'); @endphp
                @foreach (['' => 'Toutes', 'en_attente_paiement' => 'En attente', 'confirmee' => 'Confirmées', 'acompte_paye' => 'Acompte Payé', 'annulee' => 'Annulées', 'terminee' => 'Terminées'] as $val => $lab)
                    <a href="{{ route('admin.reservations.index', array_merge(request()->query(), ['statut' => $val])) }}" 
                       class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $currentStatut == $val ? 'bg-white text-red-600 shadow-sm ring-1 ring-gray-100' : 'text-gray-500 hover:text-gray-800 hover:bg-white/50' }}">
                        {{ $lab }}
                    </a>
                @endforeach
            </div>

            <div class="p-6">
                <form action="{{ route('admin.reservations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <input type="hidden" name="statut" value="{{ request('statut') }}">
                    
                    <div class="md:col-span-5">
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Référence, Nom du client, Email..." 
                                   class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-100 transition-all font-medium">
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3">
                            <i class="fas fa-calendar-day text-gray-400 text-sm"></i>
                            <input type="date" name="date_debut" value="{{ $dateDebut }}" class="w-full bg-transparent border-none py-2.5 text-sm focus:ring-0 font-medium">
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3">
                            <i class="fas fa-calendar-check text-gray-400 text-sm"></i>
                            <input type="date" name="date_fin" value="{{ $dateFin }}" class="w-full bg-transparent border-none py-2.5 text-sm focus:ring-0 font-medium">
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <button type="submit" class="w-full h-full bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all flex items-center justify-center">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reservations Table or Empty State -->
        @if ($reservations->isEmpty())
            <div class="bg-white p-8 rounded-lg border border-gray-200 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune Réservation Trouvée</h3>
                <p class="text-gray-600">Aucune réservation ne correspond à vos filtres actuels. Essayez d'ajuster vos critères de recherche.</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Client & Réf</th>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Hébergement</th>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Dates du séjour</th>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Finances</th>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">État</th>
                                <th scope="col" class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($reservations as $reservation)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-black text-xs">
                                                {{ strtoupper(substr(optional($reservation->client->user)->name ?? 'N', 0, 1)) }}{{ strtoupper(substr(optional($reservation->client->user)->prenom ?? 'N', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ optional($reservation->client->user)->name }} {{ optional($reservation->client->user)->prenom }}</div>
                                                <div class="text-[10px] font-bold text-red-500 tracking-wider">{{ $reservation->reference }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                                @if ($reservation->details->first() && $reservation->details->first()->chambre->medias->first())
                                                    <img src="{{ Storage::url($reservation->details->first()->chambre->medias->first()->chemin_fichier) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-home"></i></div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ optional($reservation->details->first()->chambre->typeChambre)->nom ?? 'N/A' }}</div>
                                                <div class="text-[11px] font-medium text-gray-500">{{ optional($reservation->details->first()->chambre->propriete)->nom ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <div class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d M Y') }}
                                            </div>
                                            <div class="text-xs font-bold text-gray-400 flex items-center gap-1.5 mt-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                                {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d M Y') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-black text-gray-900">{{ number_format($reservation->prix_total, 0, ',', ' ') }} F</div>
                                        @if($reservation->acompte_paye > 0)
                                            <div class="text-[10px] font-bold text-green-600">Acompte: {{ number_format($reservation->acompte_paye, 0, ',', ' ') }} F</div>
                                        @else
                                            <div class="text-[10px] font-bold text-amber-500">En attente de paiement</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = [
                                                'en_attente_paiement' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'En attente', 'icon' => 'fa-clock'],
                                                'acompte_paye' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'label' => 'Acompte Payé', 'icon' => 'fa-wallet'],
                                                'confirmee' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'label' => 'Confirmée', 'icon' => 'fa-check-circle'],
                                                'annulee' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Annulée', 'icon' => 'fa-times-circle'],
                                                'terminee' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => 'Terminée', 'icon' => 'fa-history'],
                                            ];
                                            $cfg = $statusConfig[$reservation->statut] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-500', 'label' => $reservation->statut, 'icon' => 'fa-info-circle'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $cfg['bg'] }} {{ $cfg['text'] }} text-[10px] font-black uppercase tracking-wider">
                                            <i class="fas {{ $cfg['icon'] }}"></i>
                                            {{ $cfg['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                                               class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 flex items-center justify-center transition-all shadow-sm"
                                               title="Détails">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            @if ($reservation->isPeutEtreModifiee())
                                                <button onclick="openStatusModal({{ $reservation->id }})" 
                                                        class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-amber-50 hover:text-amber-600 flex items-center justify-center transition-all shadow-sm"
                                                        title="Modifier le statut">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($reservations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                        {{ $reservations->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" role="dialog" aria-modal="true" aria-labelledby="statusModalTitle">
    <div class="bg-white rounded-lg border border-gray-300 w-full max-w-md mx-4">
        <form id="statusForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-600 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 id="statusModalTitle" class="ml-4 text-lg font-medium text-gray-900">Modifier le Statut de la Réservation</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="statut" id="statut" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500">
                            @foreach (['en_attente_paiement' => 'En Attente de Paiement', 'acompte_paye' => 'Acompte Payé', 'confirmee' => 'Confirmée', 'annulee' => 'Annulée', 'terminee' => 'Terminée'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="notes_admin" class="block text-sm font-medium text-gray-700 mb-2">Notes Admin</label>
                        <textarea id="notes_admin" name="notes_admin" rows="4" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-red-500 focus:ring-1 focus:ring-red-500" placeholder="Ajoutez vos notes ici..."></textarea>
                        @error('notes_admin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3 rounded-b-lg">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStatusModal(reservationId) {
        const form = document.getElementById('statusForm');
        form.action = `/admin/reservations/${reservationId}/update-status`;
        document.getElementById('statusModal').classList.remove('hidden');
        document.getElementById('statusModal').focus();
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
        document.getElementById('statusForm').reset();
    }

    // Close modal on escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !document.getElementById('statusModal').classList.contains('hidden')) {
            closeStatusModal();
        }
    });
</script>
@endsection