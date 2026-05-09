@extends('layouts.playout')

@section('title', 'Prévisions financières')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Prévisions financières</h1>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Filtres</h2>
            <form method="GET" action="{{ route('admin.finance.previsions') }}"
                  class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                <div class="md:col-span-2 flex justify-end mt-4">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Revenu attendu</h3>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($previsions['revenu_attendu'], 0, ',', ' ') }}
                    XOF</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Réservations confirmées</h3>
                <p class="text-2xl font-bold text-green-600">{{ $previsions['reservations_confirmees'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2 text-gray-700">Réservations en attente</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $previsions['reservations_en_attente'] }}</p>
            </div>
        </div>

        <!-- Graphique -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Prévisions par propriété</h2>
            <canvas id="previsionsChart" height="200"></canvas>
        </div>

        <!-- Script pour Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const previsionsChart = new Chart(document.getElementById('previsionsChart'), {
                    type: 'bar',
                    data: {
                        labels: [@foreach($previsions['par_propriete'] as $id => $montant)'{{ $proprietes[$id] }}', @endforeach],
                        datasets: [{
                            label: 'Revenu attendu (XOF)',
                            data: [@foreach($previsions['par_propriete'] as $montant){{ $montant }},@endforeach],
                            backgroundColor: '#2563EB',
                            borderColor: '#1E3A8A',
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
            });
        </script>
    </div>
@endsection