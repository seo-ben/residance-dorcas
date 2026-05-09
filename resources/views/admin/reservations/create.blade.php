@extends('layouts.playout')

@section('title', 'Créer une Réservation')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Nouvelle Réservation</h1>
                <p class="mt-2 text-gray-600">Créez manuellement une réservation pour un client.</p>
            </div>
            <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" x-data="reservationForm()">
            <form action="{{ route('admin.reservations.store') }}" method="POST" class="p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Client Selection -->
                    <div class="space-y-2">
                        <label for="id_client" class="block text-sm font-semibold text-gray-700">Client</label>
                        <select name="id_client" id="id_client" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            <option value="">Sélectionnez un client</option>
                            @foreach($clients as $user)
                                <option value="{{ $user->client->id }}" {{ old('id_client') == $user->client->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_client') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Room Selection -->
                    <div class="space-y-2">
                        <label for="id_chambre" class="block text-sm font-semibold text-gray-700">Chambre / Appartement</label>
                        <select name="id_chambre" id="id_chambre" required x-model="selectedChambre" @change="updatePrice()" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            <option value="">Sélectionnez une chambre</option>
                            @foreach($chambres as $chambre)
                                <option value="{{ $chambre->id }}" data-price="{{ $chambre->prix_base }}">
                                    {{ $chambre->typeChambre->nom }} - {{ $chambre->propriete->nom }} ({{ number_format($chambre->prix_base, 0, ',', ' ') }} F CFA/nuit)
                                </option>
                            @endforeach
                        </select>
                        @error('id_chambre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Dates -->
                    <div class="space-y-2">
                        <label for="date_arrivee" class="block text-sm font-semibold text-gray-700">Date d'arrivée</label>
                        <input type="date" name="date_arrivee" id="date_arrivee" x-model="dateArrivee" @change="updatePrice()" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                        @error('date_arrivee') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="date_depart" class="block text-sm font-semibold text-gray-700">Date de départ</label>
                        <input type="date" name="date_depart" id="date_depart" x-model="dateDepart" @change="updatePrice()" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                        @error('date_depart') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Price and Status -->
                    <div class="space-y-2">
                        <label for="prix_total" class="block text-sm font-semibold text-gray-700">Prix Total (F CFA)</label>
                        <div class="relative">
                            <input type="number" name="prix_total" id="prix_total" x-model="prixTotal" step="0.01" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors pr-12">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">F CFA</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500" x-show="nights > 0">Calculé pour <span x-text="nights"></span> nuit(s)</p>
                        @error('prix_total') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="statut" class="block text-sm font-semibold text-gray-700">Statut Initial</label>
                        <select name="statut" id="statut" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                            <option value="en_attente_paiement">En attente de paiement</option>
                            <option value="confirmee">Confirmée</option>
                            <option value="brouillon">Brouillon</option>
                        </select>
                        @error('statut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Admin Notes -->
                <div class="mt-8 space-y-2">
                    <label for="notes_admin" class="block text-sm font-semibold text-gray-700">Notes Administrateur</label>
                    <textarea name="notes_admin" id="notes_admin" rows="4" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors" placeholder="Informations complémentaires sur cette réservation..."></textarea>
                    @error('notes_admin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Payment Recording (Optional) -->
                <div class="mt-8 pt-8 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Enregistrer un paiement (Optionnel)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="montant_paye" class="block text-sm font-semibold text-gray-700">Montant déjà payé</label>
                            <input type="number" name="montant_paye" id="montant_paye" step="0.01" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label for="methode_paiement" class="block text-sm font-semibold text-gray-700">Méthode de paiement</label>
                            <select name="methode_paiement" id="methode_paiement" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                                <option value="">Choisir...</option>
                                <option value="especes">Espèces</option>
                                <option value="virement">Virement Bancaire</option>
                                <option value="tmoney">T-Money</option>
                                <option value="flooz">Flooz</option>
                                <option value="carte_credit">Carte de crédit (Sur place)</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label for="reference_paiement" class="block text-sm font-semibold text-gray-700">Référence / Reçu</label>
                            <input type="text" name="reference_paiement" id="reference_paiement" placeholder="Ex: N° Chèque, Reçu..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 transition-colors">
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-end space-x-4">
                    <button type="button" onclick="history.back()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="px-8 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold shadow-lg shadow-red-200 transition-all hover:-translate-y-0.5">
                        Enregistrer la Réservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function reservationForm() {
    return {
        selectedChambre: '',
        dateArrivee: '',
        dateDepart: '',
        prixTotal: 0,
        nights: 0,
        updatePrice() {
            if (!this.selectedChambre || !this.dateArrivee || !this.dateDepart) {
                this.nights = 0;
                return;
            }

            const start = new Date(this.dateArrivee);
            const end = new Date(this.dateDepart);
            
            if (end <= start) {
                this.nights = 0;
                this.prixTotal = 0;
                return;
            }

            const diffTime = Math.abs(end - start);
            this.nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            const select = document.getElementById('id_chambre');
            const option = select.options[select.selectedIndex];
            const pricePerNight = parseFloat(option.getAttribute('data-price')) || 0;

            this.prixTotal = (this.nights * pricePerNight).toFixed(2);
        }
    }
}
</script>
@endsection
