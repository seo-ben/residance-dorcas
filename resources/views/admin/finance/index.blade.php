@extends('layouts.playout')

@section('title', 'Tableau de bord financier')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Tableau de bord financier</h1>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Filtres</h2>
            <form method="GET" action="{{ route('admin.finance.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label for="propriete_id" class="block text-sm font-medium text-gray-700">Propriété</label>
                    <select name="propriete_id" id="propriete_id"
                            class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Toutes</option>
                        @foreach ($proprietes as $id => $nom)
                            <option value="{{ $id }}" {{ request('propriete_id') == $id ? 'selected' : '' }}>
                                {{ $nom }}
                            </option>
                        @endforeach
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
                    <a href="{{ route('admin.finance.export') }}?date_debut={{ $dateDebut }}&date_fin={{ $dateFin }}"
                       class="ml-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Exporter Excel
                    </a>                   
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Revenus aujourd'hui</h3>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($statistiques['total_jour'], 0, ',', ' ') }}
                    XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Revenus ce mois</h3>
                <p class="text-2xl font-bold text-green-600">{{ number_format($statistiques['total_mois'], 0, ',', ' ') }}
                    XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Revenus cette année</h3>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($statistiques['total_annee'], 0, ',', ' ') }}
                    XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">RevPAR</h3>
                <p class="text-2xl font-bold text-orange-600">{{ number_format($statistiques['revpar'], 2, ',', ' ') }}
                    XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
                {{-- <h3 class="text-lg font-semibold mb-2 text-gray-700">Taux d'occupation</h3> --}}
                {{-- <p class="text-2xl font-bold text-teal-600">{{ number_format($statistiques['taux_occupation'], 2, ',', ' ') }} %</p> --}}
            </div>
            <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Paiements en attente</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $statistiques['en_attente'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Paiements refusés</h3>
                <p class="text-2xl font-bold text-red-600">{{ $statistiques['refuse'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 transform hover:scale-105 transition-transform duration-200">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Remboursements</h3>
                <p class="text-2xl font-bold text-gray-600">{{ number_format($statistiques['rembourse'], 0, ',', ' ') }}
                    XOF</p>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Tendances financières</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Revenus par période</h3>
                    <canvas id="revenusChart" height="200"></canvas>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">RevPAR et Taux d'occupation</h3>
                    <canvas id="kpiChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Liste des derniers paiements -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-xl font-semibold">Derniers paiements</h2>
                <a href="{{ route('admin.finance.transactions') }}"
                   class="text-indigo-600 hover:text-indigo-900 font-medium">Voir toutes les transactions</a>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant Remboursé</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($paiements as $paiement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($paiement->date_paiement instanceof \DateTime || $paiement->date_paiement instanceof \Carbon\Carbon)
                                        {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $paiement->reference_transaction }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $paiement->reservation && $paiement->reservation->client && $paiement->reservation->client->user ? $paiement->reservation->client->user->name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $paiement->reservation && $paiement->reservation->details->first() && $paiement->reservation->details->first()->chambre && $paiement->reservation->details->first()->chambre->propriete ? $paiement->reservation->details->first()->chambre->propriete->nom : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                    {{ number_format($paiement->montant, 0, ',', ' ') }} XOF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                    {{ number_format($paiement->montant_rembourse, 0, ',', ' ') }} XOF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $paiement->methode_paiement ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paiement->statut === 'valide' ? 'bg-green-100 text-green-800' : ($paiement->statut === 'rembourse' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($paiement->statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.finance.transactions') }}?reference={{ $paiement->reference_transaction }}"
                                    class="text-indigo-600 hover:text-indigo-900">Détails</a>
                                    @if($paiement->statut === 'valide')
                                        <a href="#" 
                                        data-id="{{ $paiement->id }}" 
                                        data-montant="{{ $paiement->montant }}" 
                                        data-rembourse="{{ $paiement->montant_rembourse }}"
                                        class="text-red-600 hover:text-red-900 ml-4 refund-link">Rembourser</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t">
                {{ $paiements->links() }}
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
                    <p class="text-sm text-gray-500 mt-1" id="max-remboursable">Maximum remboursable : <span id="montant-max">0</span> XOF</p>
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

    <!-- Scripts pour Chart.js et gestion du modal -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Graphique des revenus
            const revenusChart = new Chart(document.getElementById('revenusChart'), {
                type: 'bar',
                data: {
                    labels: ['Aujourd\'hui', 'Ce mois', 'Cette année'],
                    datasets: [{
                        label: 'Revenus (XOF)',
                        data: [
                            {{ $statistiques['total_jour'] }},
                            {{ $statistiques['total_mois'] }},
                            {{ $statistiques['total_annee'] }}
                        ],
                        backgroundColor: ['#2563EB', '#16A34A', '#7C3AED'],
                        borderColor: ['#1E3A8A', '#15803D', '#6B21A8'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Montant (XOF)'
                            }
                        }
                    }
                }
            });

            // Graphique RevPAR et Taux d'occupation
            const kpiChart = new Chart(document.getElementById('kpiChart'), {
                type: 'line',
                data: {
                    labels: ['RevPAR', 'Taux d\'occupation'],
                    datasets: [{
                        label: 'Valeur',
                        data: [
                            {{ $statistiques['revpar'] }},
                            {{ $statistiques['taux_occupation'] }}
                        ],
                        backgroundColor: '#F59E0B',
                        borderColor: '#B45309',
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Valeur'
                            }
                        }
                    }
                }
            });


            // Gestion du modal de remboursement
            const refundLinks = document.querySelectorAll('.refund-link');
            const refundModal = document.getElementById('refundModal');
            const refundForm = document.getElementById('refundForm');
            const cancelRefund = document.getElementById('cancelRefund');
            const montantInput = document.getElementById('montant');
            const montantMax = document.getElementById('montant-max');

            refundLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const paiementId = this.getAttribute('data-id');
                    const montant = parseFloat(this.getAttribute('data-montant'));
                    const montantRembourse = parseFloat(this.getAttribute('data-rembourse'));
                    const maxRemboursable = montant - montantRembourse;
                    
                    document.getElementById('paiement_id').value = paiementId;
                    montantInput.max = maxRemboursable;
                    montantMax.textContent = new Intl.NumberFormat('fr-FR').format(maxRemboursable);
                    
                    refundForm.action = '{{ route('admin.paiements.refund', ['paiement' => ':id']) }}'.replace(':id', paiementId);
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