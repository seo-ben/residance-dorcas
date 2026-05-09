@extends('layouts.playout')

@section('title', 'Rapports financiers')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Rapports financiers</h1>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Filtres</h2>
            <form method="GET" action="{{ route('admin.finance.rapports') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                    <input type="date" name="date_debut" id="date_debut" value="{{ $debut }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                    <input type="date" name="date_fin" id="date_fin" value="{{ $fin }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="propriete_id" class="block text-sm font-medium text-gray-700">Propriété</label>
                    <select name="propriete_id" id="propriete_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Toutes</option>
                        @foreach ($proprietes as $id => $nom)
                            <option value="{{ $id }}" {{ request('propriete_id') == $id ? 'selected' : '' }}>
                                {{ $nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3 flex justify-end mt-4">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Filtrer
                    </button>
                    <a href="{{ route('admin.finance.export') }}?date_debut={{ $debut }}&date_fin={{ $fin }}"
                       class="ml-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Exporter Excel
                    </a>
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Total des revenus</h3>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($statistiques['total_periode'], 0, ',', ' ') }}
                    XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Nombre de réservations</h3>
                <p class="text-2xl font-bold text-green-600">{{ $statistiques['nombre_reservations'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Revenu moyen par réservation</h3>
                <p class="text-2xl font-bold text-purple-600">
                    {{ number_format($statistiques['moyenne_reservation'], 0, ',', ' ') }} XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">TVA collectée</h3>
                <p class="text-2xl font-bold text-orange-600">{{ number_format($statistiques['taxes']['tva'], 0, ',', ' ') }}
                    XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Taxe de séjour</h3>
                <p class="text-2xl font-bold text-teal-600">
                    {{ number_format($statistiques['taxes']['taxe_sejour'], 0, ',', ' ') }} XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">RevPAR</h3>
                <p class="text-2xl font-bold text-indigo-600">{{ number_format($statistiques['revpar'], 2, ',', ' ') }}
                    XOF</p>
            </div>
        </div>

        <!-- Graphique -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Répartition des revenus</h2>
            <canvas id="revenusParProprieteChart" height="200"></canvas>
        </div>

        <!-- Tableau des paiements -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Détails des paiements</h2>
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
                            <td class="px-6 py-4 whitespace-nowrap">{{ $paiement->methode_paiement ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paiement->statut === 'valide' ? 'bg-green-100 text-green-800' : ($paiement->statut === 'rembourse' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($paiement->statut) }}
                                </span>
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

    <!-- Script pour Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revenusParProprieteChart = new Chart(document.getElementById('revenusParProprieteChart'), {
                type: 'pie',
                data: {
                    labels: [@foreach($statistiques['par_propriete'] as $id => $montant)'{{ $proprietes[$id] }}', @endforeach],
                    datasets: [{
                        label: 'Revenus par propriété',
                        data: [@foreach($statistiques['par_propriete'] as $montant){{ $montant }},@endforeach],
                        backgroundColor: ['#2563EB', '#16A34A', '#7C3AED', '#F59E0B', '#DC2626'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.raw) + ' XOF';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection