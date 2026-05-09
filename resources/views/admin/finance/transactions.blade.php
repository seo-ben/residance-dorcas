@extends('layouts.playout')

@section('title', 'Historique des transactions')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Historique des transactions</h1>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Filtres</h2>
            <form method="GET" action="{{ route('admin.finance.transactions') }}"
                  class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                        <option value="">Tous</option>
                        <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Valide</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente
                        </option>
                        <option value="refuse" {{ request('statut') == 'refuse' ? 'selected' : '' }}>Refusé</option>
                        <option value="rembourse" {{ request('statut') == 'rembourse' ? 'selected' : '' }}>Remboursé
                        </option>
                    </select>
                </div>
                <div>
                    <label for="methode_paiement" class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
                    <select name="methode_paiement" id="methode_paiement"
                            class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Toutes</option>
                        @foreach ($methodesPaiement as $methode)
                            <option value="{{ $methode }}"
                                    {{ request('methode_paiement') == $methode ? 'selected' : '' }}>
                                {{ ucfirst($methode) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end mt-4">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau des transactions -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Liste des transactions</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriété</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($transaction->date_paiement instanceof \DateTime || $transaction->date_paiement instanceof \Carbon\Carbon)
                                    {{ $transaction->date_paiement->format('d/m/Y H:i') }}
                                @else
                                    {{ \Carbon\Carbon::parse($transaction->date_paiement)->format('d/m/Y H:i') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->reference_transaction }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transaction->reservation && $transaction->reservation->client && $transaction->reservation->client->user ? $transaction->reservation->client->user->name : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $transaction->reservation && $transaction->reservation->details->first() && $transaction->reservation->details->first()->chambre && $transaction->reservation->details->first()->chambre->propriete ? $transaction->reservation->details->first()->chambre->propriete->nom : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                {{ number_format($transaction->montant, 0, ',', ' ') }} XOF
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->methode_paiement ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->statut === 'valide' ? 'bg-green-100 text-green-800' : ($transaction->statut === 'rembourse' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($transaction->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($transaction->statut === 'valide')
                                    <a href="#" data-id="{{ $transaction->id }}"
                                       class="text-red-600 hover:text-red-900 refund-link">Rembourser</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de remboursement -->
    <div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Rembourser un paiement</h2>
            <form id="refundForm" method="POST" action="">
                @csrf
                <input type="hidden" name="paiement_id" id="paiement_id">
                <div class="mb-4">
                    <label for="montant" class="block text-sm font-medium text-gray-700">Montant (XOF)</label>
                    <input type="number" name="montant" id="montant" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="raison" class="block text-sm font-medium text-gray-700">Raison du remboursement</label>
                    <textarea name="raison" id="raison" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancelRefund"
                            class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Confirmer le remboursement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script pour gestion du modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const refundLinks = document.querySelectorAll('.refund-link');
            const refundModal = document.getElementById('refundModal');
            const refundForm = document.getElementById('refundForm');
            const cancelRefund = document.getElementById('cancelRefund');

            refundLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const paiementId = this.getAttribute('data-id');
                    document.getElementById('paiement_id').value = paiementId;
                    refundForm.action = '{{ route('admin.finance.refund', ['paiement' => ':id']) }}'.replace(':id', paiementId);
                    refundModal.classList.remove('hidden');
                });
            });

            cancelRefund.addEventListener('click', function () {
                refundModal.classList.add('hidden');
                refundForm.reset();
            });
        });
    </script>
@endsection