@extends('layouts.playout')

@section('title', 'Nouvel Encaissement - Résidence Dorcas')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="encaissementForm()">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                Point d'<span class="text-red-600">Encaissement</span>
            </h1>
            <p class="text-gray-500 font-medium">Enregistrez un paiement pour n'importe quel service de la résidence.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.finance.transactions') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                <i class="fas fa-history mr-2 text-red-500"></i> Historique
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 overflow-hidden border border-gray-100">
                <div class="p-8 bg-gradient-to-br from-white to-gray-50/50">
                    <form action="{{ route('admin.finance.encaissement.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Step 1: Type Selection -->
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">1. Type de Service</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="type" value="reservation" x-model="type" @change="itemId = ''" class="peer sr-only" required>
                                        <div class="p-4 bg-white border-2 border-gray-100 rounded-2xl peer-checked:border-red-500 peer-checked:bg-red-50 transition-all group-hover:border-gray-200">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-bed"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">Hébergement</p>
                                                    <p class="text-[10px] text-gray-500 font-medium">Chambres & Apparts</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="type" value="location_vehicule" x-model="type" @change="itemId = ''" class="peer sr-only">
                                        <div class="p-4 bg-white border-2 border-gray-100 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all group-hover:border-gray-200">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-car"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">Véhicule</p>
                                                    <p class="text-[10px] text-gray-500 font-medium">Location voiture</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="type" value="commande_service" x-model="type" @change="itemId = ''" class="peer sr-only">
                                        <div class="p-4 bg-white border-2 border-gray-100 rounded-2xl peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all group-hover:border-gray-200">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-concierge-bell"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">Services</p>
                                                    <p class="text-[10px] text-gray-500 font-medium">Repas & Extras</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Step 2: Item Selection -->
                            <div x-show="type" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">2. Sélectionner l'élément à payer</label>
                                <select name="item_id" x-model="itemId" @change="updateSelection()" class="w-full bg-white border-2 border-gray-100 rounded-2xl p-4 text-sm font-bold text-gray-700 focus:ring-red-500 focus:border-red-500 transition-all outline-none" required>
                                    <option value="">-- Choisir une référence --</option>
                                    
                                    <!-- Options Réservations -->
                                    <template x-if="type === 'reservation'">
                                        @foreach($reservations as $res)
                                            <option value="{{ $res->id }}" 
                                                data-amount="{{ $res->prix_total - $res->acompte_paye }}"
                                                data-client="{{ $res->client->user->name }} {{ $res->client->user->prenom }}"
                                                data-ref="{{ $res->reference }}">
                                                {{ $res->reference }} - {{ $res->client->user->name }} (Reste: {{ number_format($res->prix_total - $res->acompte_paye, 0, ',', ' ') }} FCFA)
                                            </option>
                                        @endforeach
                                    </template>

                                    <!-- Options Véhicules -->
                                    <template x-if="type === 'location_vehicule'">
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}"
                                                data-amount="{{ $loc->prix_total }}"
                                                data-client="{{ $loc->client->user->name }} {{ $loc->client->user->prenom }}"
                                                data-ref="Location #{{ $loc->id }}">
                                                Location #{{ $loc->id }} - {{ $loc->vehicule->marque }} - {{ $loc->client->user->name }} (Total: {{ number_format($loc->prix_total, 0, ',', ' ') }} FCFA)
                                            </option>
                                        @endforeach
                                    </template>

                                    <!-- Options Services -->
                                    <template x-if="type === 'commande_service'">
                                        @foreach($commandes as $com)
                                            <option value="{{ $com->id }}"
                                                data-amount="{{ $com->prix_total }}"
                                                data-client="{{ $com->client->user->name }} {{ $com->client->user->prenom }}"
                                                data-ref="Commande #{{ $com->id }}">
                                                Commande #{{ $com->id }} - {{ $com->client->user->name }} (Total: {{ number_format($com->prix_total, 0, ',', ' ') }} FCFA)
                                            </option>
                                        @endforeach
                                    </template>
                                </select>
                            </div>

                            <!-- Step 3: Payment Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="itemId" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">3. Montant à encaisser (FCFA)</label>
                                    <input type="number" name="montant" x-model="amount" class="w-full bg-white border-2 border-gray-100 rounded-2xl p-4 text-xl font-black text-red-600 focus:ring-red-500 focus:border-red-500 transition-all outline-none" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">4. Méthode de paiement</label>
                                    <select name="methode_paiement" class="w-full bg-white border-2 border-gray-100 rounded-2xl p-4 text-sm font-bold text-gray-700 focus:ring-red-500 focus:border-red-500 transition-all outline-none" required>
                                        <option value="especes">Espèces</option>
                                        <option value="virement">Virement Bancaire</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="carte_credit">Carte de Crédit</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Référence transaction / Notes</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <input type="text" name="reference_transaction" placeholder="N° de reçu, ID transaction..." class="w-full bg-white border-2 border-gray-100 rounded-2xl p-4 text-sm font-medium text-gray-700 focus:ring-red-500 focus:border-red-500 transition-all outline-none">
                                        <input type="text" name="notes" placeholder="Note interne (optionnel)" class="w-full bg-white border-2 border-gray-100 rounded-2xl p-4 text-sm font-medium text-gray-700 focus:ring-red-500 focus:border-red-500 transition-all outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10" x-show="itemId">
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-4 px-8 rounded-2xl shadow-xl shadow-red-100 transition-all flex items-center justify-center space-x-3 group">
                                <i class="fas fa-check-circle text-lg group-hover:scale-125 transition-transform"></i>
                                <span>VALIDER L'ENCAISSEMENT</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Column -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 p-8 border border-gray-100 sticky top-8">
                <h2 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-red-500 mr-2"></i> Récapitulatif
                </h2>

                <div x-show="!itemId" class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calculator text-2xl"></i>
                    </div>
                    <p class="text-gray-400 text-sm font-medium">Sélectionnez un élément pour voir les détails de l'encaissement.</p>
                </div>

                <div x-show="itemId" class="space-y-6">
                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Client</p>
                        <p class="text-sm font-black text-gray-900" x-text="clientName"></p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-2xl">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Référence</p>
                        <p class="text-sm font-black text-gray-900" x-text="itemRef"></p>
                    </div>

                    <div class="border-t-2 border-dashed border-gray-100 pt-6">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm text-gray-500 font-bold">Total à régler</p>
                            <p class="text-sm font-black text-gray-900" x-text="formatPrice(totalToPay)"></p>
                        </div>
                        <div class="flex justify-between items-center text-xl font-black text-red-600">
                            <p>Encaissé</p>
                            <p x-text="formatPrice(amount)"></p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-start space-x-3 p-4 bg-yellow-50 rounded-2xl text-yellow-800 border border-yellow-100">
                        <i class="fas fa-exclamation-triangle mt-1"></i>
                        <p class="text-[11px] font-bold leading-relaxed">
                            Cette action est irréversible. Un reçu de paiement sera généré et le client sera notifié par email.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function encaissementForm() {
        return {
            type: '',
            itemId: '',
            clientName: '',
            itemRef: '',
            totalToPay: 0,
            amount: 0,

            updateSelection() {
                if (!this.itemId) {
                    this.clientName = '';
                    this.itemRef = '';
                    this.totalToPay = 0;
                    this.amount = 0;
                    return;
                }
                
                // Attendre un tick pour que le DOM soit à jour avec les templates x-if
                setTimeout(() => {
                    const select = document.querySelector('select[name="item_id"]');
                    const option = select.options[select.selectedIndex];
                    
                    if (option) {
                        this.totalToPay = option.dataset.amount;
                        this.amount = this.totalToPay;
                        this.clientName = option.dataset.client;
                        this.itemRef = option.dataset.ref;
                    }
                }, 10);
            },

            formatPrice(value) {
                return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
            }
        }
    }
</script>
@endsection
