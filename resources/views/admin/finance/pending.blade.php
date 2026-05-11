@extends('layouts.playout')

@section('title', 'Paiements à Valider - Résidence Dorcas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                Paiements <span class="text-yellow-500">en attente</span>
            </h1>
            <p class="text-gray-500 font-medium">Validez les captures d'écran et les transferts reçus.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-xl text-sm font-black shadow-sm">
                {{ $pendingPayments->count() }} à vérifier
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3 text-lg"></i>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if($pendingPayments->isEmpty())
        <div class="bg-white rounded-3xl p-16 text-center shadow-xl shadow-gray-100 border border-gray-100">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-double text-4xl text-gray-200"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-2">Tout est à jour !</h2>
            <p class="text-gray-400 font-medium max-w-sm mx-auto">Il n'y a aucun paiement en attente de validation pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($pendingPayments as $p)
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden group hover:border-primary-200 transition-all">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left Info -->
                        <div class="p-8 flex-1">
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    {{ $p->id_reservation ? 'Hébergement' : ($p->id_location_vehicule ? 'Véhicule' : 'Service') }}
                                </span>
                                <span class="text-xs text-gray-400 font-bold">Posté {{ $p->created_at->diffForHumans() }}</span>
                            </div>

                            <h3 class="text-xl font-black text-gray-900 mb-2">
                                @if($p->reservation)
                                    {{ $p->reservation->client->user->name }} {{ $p->reservation->client->user->prenom }}
                                @elseif($p->locationVehicule)
                                    {{ $p->locationVehicule->client->user->name }} {{ $p->locationVehicule->client->user->prenom }}
                                @elseif($p->commandeService)
                                    {{ $p->commandeService->client->user->name }} {{ $p->commandeService->client->user->prenom }}
                                @endif
                            </h3>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="p-3 bg-red-50 rounded-2xl">
                                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Montant</p>
                                    <p class="text-lg font-black text-red-600">{{ number_format($p->montant, 0, ',', ' ') }} FCFA</p>
                                </div>
                                <div class="p-3 bg-blue-50 rounded-2xl">
                                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Méthode</p>
                                    <p class="text-sm font-black text-blue-600 uppercase">{{ str_replace('_', ' ', $p->methode_paiement) }}</p>
                                </div>
                            </div>

                            @if($p->reference_transaction)
                                <div class="mb-6">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Référence Transaction</p>
                                    <p class="text-sm font-bold text-gray-700 bg-gray-50 p-2 rounded-lg inline-block">{{ $p->reference_transaction }}</p>
                                </div>
                            @endif

                            @if($p->notes)
                                <div class="mb-6">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Notes client</p>
                                    <p class="text-sm text-gray-500 italic">"{{ $p->notes }}"</p>
                                </div>
                            @endif
                        </div>

                        <!-- Proof / Screenshot -->
                        <div class="md:w-72 bg-gray-100 flex items-center justify-center p-4 border-l border-gray-100">
                            @if($p->preuve_paiement)
                                <a href="{{ Storage::url($p->preuve_paiement) }}" target="_blank" class="relative group/img cursor-zoom-in">
                                    <img src="{{ Storage::url($p->preuve_paiement) }}" class="max-h-64 rounded-xl shadow-lg group-hover/img:scale-[1.02] transition-transform duration-300" alt="Preuve de paiement">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center rounded-xl">
                                        <i class="fas fa-search-plus text-white text-2xl"></i>
                                    </div>
                                </a>
                            @else
                                <div class="text-center p-8 text-gray-400">
                                    <i class="fas fa-image text-4xl mb-3"></i>
                                    <p class="text-xs font-bold">Aucune preuve visuelle fournie</p>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="p-8 bg-gray-50/50 flex flex-col justify-center space-y-3 min-w-[200px]">
                            <form action="{{ route('admin.finance.paiements.approve', $p->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-3 rounded-xl shadow-lg shadow-green-100 transition-all flex items-center justify-center space-x-2">
                                    <i class="fas fa-check"></i>
                                    <span>VALIDER</span>
                                </button>
                            </form>

                            <button onclick="openRejectModal({{ $p->id }})" class="w-full bg-white border border-red-200 text-red-500 hover:bg-red-50 font-black py-3 rounded-xl transition-all flex items-center justify-center space-x-2">
                                <i class="fas fa-times"></i>
                                <span>REJETER</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl scale-95 transition-transform duration-300" id="rejectModalContent">
        <h3 class="text-2xl font-black text-gray-900 mb-4">Rejeter le paiement</h3>
        <p class="text-gray-500 font-medium mb-6">Précisez la raison du rejet pour informer le client.</p>
        
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="reason" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 text-sm font-medium focus:ring-red-500 focus:border-red-500 outline-none mb-6" rows="4" placeholder="Ex: Montant incorrect, Preuve illisible..." required></textarea>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 bg-gray-100 text-gray-500 font-black rounded-xl hover:bg-gray-200 transition-all">ANNULER</button>
                <button type="submit" class="flex-1 py-3 bg-red-600 text-white font-black rounded-xl shadow-lg shadow-red-100 hover:bg-red-700 transition-all">REJETER</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const content = document.getElementById('rejectModalContent');
        const form = document.getElementById('rejectForm');
        
        form.action = `/admin/finance/paiements/${id}/rejeter`;
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.remove('scale-95'), 10);
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        const content = document.getElementById('rejectModalContent');
        
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>
@endsection
