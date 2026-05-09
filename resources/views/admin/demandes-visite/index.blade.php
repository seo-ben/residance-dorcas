@extends('layouts.playout')

@section('title', 'Demandes de visite')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Demandes de visite</h1>
            <p class="text-gray-500 font-medium">Gérez et planifiez les visites d'appartements pour vos prospects</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">En attente</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['en_attente'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Visites Aujourd'hui</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['aujourdhui'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-week text-indigo-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Cette semaine</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['cette_semaine'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alerts')

    <!-- Filtres -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.demandes-visite.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Statut</label>
                <select name="statut" class="w-full bg-gray-50 border-none rounded-xl text-sm py-2.5 focus:ring-2 focus:ring-red-500/20">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                    <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                    <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Date début</label>
                <input type="date" name="date_debut" value="{{ $dateDebut }}" class="w-full bg-gray-50 border-none rounded-xl text-sm py-2.5 focus:ring-2 focus:ring-red-500/20">
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Date fin</label>
                <input type="date" name="date_fin" value="{{ $dateFin }}" class="w-full bg-gray-50 border-none rounded-xl text-sm py-2.5 focus:ring-2 focus:ring-red-500/20">
            </div>
            <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-800 transition-all">
                <i class="fas fa-filter mr-2"></i> Filtrer
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Client / Contact</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Appartement cible</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Demande / Souhait</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $demande->client->user->name ?? 'N/A' }} {{ $demande->client->user->prenom ?? '' }}</div>
                            <div class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                <i class="fas fa-phone text-[10px] text-gray-300"></i>
                                {{ $demande->client->user->telephone ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-800">{{ $demande->chambre->typeChambre->nom ?? 'Type inconnu' }}</div>
                            <div class="text-xs text-gray-500">{{ $demande->chambre->propriete->nom ?? 'Propriété N/A' }} ({{ $demande->chambre->numero_chambre }})</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Créée le {{ \Carbon\Carbon::parse($demande->date_demande)->format('d/m/Y') }}</div>
                            <div class="text-sm font-bold text-red-600 mt-1">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ \Carbon\Carbon::parse($demande->date_visite_souhaitee)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'en_attente' => ['class' => 'bg-yellow-50 text-yellow-700', 'icon' => 'fa-clock', 'label' => 'En attente'],
                                    'confirmee' => ['class' => 'bg-green-50 text-green-700', 'icon' => 'fa-check-circle', 'label' => 'Confirmée'],
                                    'terminee' => ['class' => 'bg-indigo-50 text-indigo-700', 'icon' => 'fa-flag-checkered', 'label' => 'Terminée'],
                                    'annulee' => ['class' => 'bg-red-50 text-red-700', 'icon' => 'fa-times-circle', 'label' => 'Annulée'],
                                ];
                                $config = $statusConfig[$demande->statut] ?? $statusConfig['en_attente'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $config['class'] }}">
                                <i class="fas {{ $config['icon'] }}"></i>
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($demande->statut == 'en_attente')
                            <div class="flex justify-end gap-2">
                                <button onclick="openConfirmModal({{ $demande->id }})" class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-bold hover:bg-green-100 transition-all">
                                    Confirmer
                                </button>
                                <button onclick="openRejectModal({{ $demande->id }})" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-bold hover:bg-red-100 transition-all">
                                    Rejeter
                                </button>
                            </div>
                            @else
                                <span class="text-xs font-bold text-gray-300">Traitée</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-calendar-times text-4xl mb-4 opacity-20"></i>
                                <p>Aucune demande trouvée</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($demandes->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $demandes->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Confirmation -->
<div id="confirmModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <form id="confirmForm" method="POST">
            @csrf @method('PUT')
            <div class="p-6">
                <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Planifier la visite</h3>
                <p class="text-sm text-gray-500 mb-6">Confirmez la date et l'heure définitives pour cette visite.</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Date & Heure</label>
                        <input type="datetime-local" name="date_confirmation" required class="w-full bg-gray-50 border-none rounded-xl text-sm py-3 focus:ring-2 focus:ring-green-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Notes pour le client</label>
                        <textarea name="notes_admin" rows="3" class="w-full bg-gray-50 border-none rounded-xl text-sm py-3 focus:ring-2 focus:ring-green-500/20" placeholder="Ex: Veuillez vous présenter à l'accueil..."></textarea>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="closeConfirmModal()" class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-all">Annuler</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-xl shadow-lg shadow-green-200 transition-all">Confirmer la visite</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Rejet -->
<div id="rejectModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <form id="rejectForm" method="POST">
            @csrf @method('PUT')
            <div class="p-6">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Rejeter la demande</h3>
                <p class="text-sm text-gray-500 mb-6">Veuillez indiquer le motif du rejet pour informer le client.</p>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Raison du rejet</label>
                    <textarea name="notes_admin" rows="4" required class="w-full bg-gray-50 border-none rounded-xl text-sm py-3 focus:ring-2 focus:ring-red-500/20" placeholder="Ex: Appartement non disponible à cette date..."></textarea>
                </div>
            </div>
            <div class="p-6 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-all">Annuler</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-200 transition-all">Confirmer le rejet</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openConfirmModal(id) {
        document.getElementById('confirmForm').action = `/admin/demandes-visite/${id}/confirm`;
        document.getElementById('confirmModal').classList.remove('hidden');
    }
    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }
    function openRejectModal(id) {
        document.getElementById('rejectForm').action = `/admin/demandes-visite/${id}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
    window.onclick = function(event) {
        if (event.target == document.getElementById('confirmModal')) closeConfirmModal();
        if (event.target == document.getElementById('rejectModal')) closeRejectModal();
    }
</script>
@endsection