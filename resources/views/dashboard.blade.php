@extends('layouts.playout')

@section('title', 'Tableau de Bord')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Content de vous revoir, {{ Auth::user()->name }} 👋</h1>
            <p class="text-slate-500 text-sm mt-1">Voici ce qui se passe dans votre résidence aujourd'hui.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reservations.create') }}" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl text-sm font-medium hover:bg-primary-700 transition-all duration-200 shadow-lg shadow-primary-200 flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Nouvelle Réservation
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Reservations -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:border-primary-200 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <span class="flex items-center text-[10px] font-bold text-green-500 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fa-solid fa-arrow-up mr-1"></i> +{{ $stats['reservations_prochaines'] }}
                </span>
            </div>
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider">Réservations Totales</h3>
            <div class="flex items-baseline mt-1">
                <span class="text-3xl font-bold text-slate-800">{{ $stats['total_reservations'] }}</span>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 text-[11px] flex justify-between">
                <span class="text-slate-400">Cette semaine</span>
                <span class="font-bold text-slate-700">+{{ $stats['reservations_prochaines'] }}</span>
            </div>
        </div>

        <!-- Occupancy -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:border-primary-200 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-house-circle-check text-xl"></i>
                </div>
            </div>
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider">Taux d'Occupation</h3>
            <div class="flex items-baseline mt-1">
                <span class="text-3xl font-bold text-slate-800">{{ number_format($stats['taux_occupation'], 1) }}%</span>
            </div>
            <div class="mt-4 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ min($stats['taux_occupation'], 100) }}%"></div>
            </div>
            <div class="mt-3 text-[11px] text-slate-400">{{ $stats['reservations_en_cours'] }} unités occupées</div>
        </div>

        <!-- Revenue -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:border-primary-200 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-primary-50 text-primary-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-coins text-xl"></i>
                </div>
                <span class="text-[10px] font-bold text-primary-500 bg-primary-50 px-2 py-1 rounded-full">Aujourd'hui</span>
            </div>
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider">Revenus du Jour</h3>
            <div class="flex items-baseline mt-1">
                <span class="text-2xl font-bold text-slate-800">{{ number_format($stats['montant_aujourdhui'], 0, ',', ' ') }}</span>
                <span class="ml-1 text-sm font-bold text-slate-400">FCFA</span>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 grid grid-cols-2 gap-4 text-[11px]">
                <div>
                    <p class="text-slate-400 uppercase font-bold">Ce Mois</p>
                    <p class="font-bold text-slate-700">{{ number_format($stats['montant_mois'], 0, ',', ' ') }} F</p>
                </div>
                <div class="text-right">
                    <p class="text-slate-400 uppercase font-bold">Total Année</p>
                    <p class="font-bold text-primary-600">{{ number_format($stats['montant_total'], 0, ',', ' ') }} F</p>
                </div>
            </div>
        </div>

        <!-- Visit Requests -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:border-primary-200 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-person-walking-arrow-right text-xl"></i>
                </div>
                @if($stats['demandes_visite_en_attente'] > 0)
                <div class="h-2.5 w-2.5 bg-amber-500 rounded-full animate-pulse mt-3"></div>
                @endif
            </div>
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider">Demandes de Visite</h3>
            <div class="flex items-baseline mt-1">
                <span class="text-3xl font-bold text-slate-800">{{ $stats['demandes_visite_en_attente'] }}</span>
                <span class="ml-2 text-xs text-slate-400">en attente</span>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.demandes-visite.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-50 text-slate-600 text-[11px] font-bold rounded-lg hover:bg-amber-50 hover:text-amber-600 transition-colors">
                    Traiter les demandes <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Additional KPIs for Services & Vehicles -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Service Orders KPI -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-blue-200 transition-all duration-300">
            <div class="flex items-center">
                <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl mr-4 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-bell-concierge text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider">Commandes Services</h3>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['commandes_services_en_attente'] }} <span class="text-sm font-medium text-slate-400">en attente</span></p>
                </div>
            </div>
            <a href="{{ route('admin.services.orders') }}" class="p-2 bg-slate-50 text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>

        <!-- Vehicle Rentals KPI -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-indigo-200 transition-all duration-300">
            <div class="flex items-center">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl mr-4 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-car text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider">Locations Actives</h3>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['locations_vehicules_actives'] }} <span class="text-sm font-medium text-slate-400">en cours</span></p>
                </div>
            </div>
            <a href="{{ route('admin.vehicules.rentals') }}" class="p-2 bg-slate-50 text-slate-400 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Main Chart -->
        <div class="xl:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Réservations & Revenus</h3>
                    <p class="text-xs text-slate-400 mt-1">Données réelles — Année {{ date('Y') }}</p>
                </div>
                <div class="flex bg-slate-50 p-1 rounded-xl" x-data="{ tab: 'reservations' }">
                    <button @click="tab = 'reservations'; toggleDataset(0)" :class="tab === 'reservations' ? 'bg-white shadow-sm text-primary-600' : 'text-slate-500'" class="px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all">Réservations</button>
                    <button @click="tab = 'revenus'; toggleDataset(1)" :class="tab === 'revenus' ? 'bg-white shadow-sm text-primary-600' : 'text-slate-500'" class="px-4 py-1.5 text-[11px] font-bold rounded-lg transition-all">Revenus</button>
                </div>
            </div>
            <div class="h-[320px]">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

        <!-- Doughnut -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Occupation</h3>
            <p class="text-xs text-slate-400 mb-6">Répartition actuelle des unités</p>
            <div class="h-[220px] flex items-center justify-center relative">
                <canvas id="occupancyChart"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="text-center">
                        <span class="text-3xl font-bold text-slate-800">{{ number_format($stats['taux_occupation'], 0) }}%</span>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Occupé</p>
                    </div>
                </div>
            </div>
            <div class="mt-8 space-y-3">
                <div class="flex justify-between items-center text-xs">
                    <span class="flex items-center text-slate-500"><span class="h-2.5 w-2.5 rounded-full bg-primary-500 mr-2"></span> Occupé</span>
                    <span class="font-bold text-slate-700">{{ $stats['reservations_en_cours'] }} unités</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="flex items-center text-slate-500"><span class="h-2.5 w-2.5 rounded-full bg-slate-200 mr-2"></span> Disponible</span>
                    <span class="font-bold text-slate-700">{{ $stats['total_chambres'] ?? 0 }} total</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Reservations -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Réservations Récentes</h3>
                <a href="{{ route('admin.reservations.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700">Tout voir →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($reservationsRecentes as $reservation)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-9 w-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold mr-3 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors">
                                        {{ strtoupper(substr($reservation->client->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $reservation->client->user->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($reservation->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-700">{{ number_format($reservation->prix_total, 0, ',', ' ') }} F</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $sc = match($reservation->statut) {
                                        'confirmee' => 'bg-emerald-50 text-emerald-600',
                                        'en_attente' => 'bg-amber-50 text-amber-600',
                                        'annulee' => 'bg-red-50 text-red-600',
                                        'terminee' => 'bg-blue-50 text-blue-600',
                                        default => 'bg-slate-50 text-slate-600',
                                    };
                                @endphp
                                <span class="px-3 py-1.5 text-[10px] font-bold rounded-full {{ $sc }}">
                                    {{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <i class="fa-regular fa-calendar text-3xl text-slate-200 mb-3 block"></i>
                                <p class="text-sm text-slate-400">Aucune réservation récente</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Flux de Trésorerie</h3>
                <a href="{{ route('admin.finance.transactions') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700">Détails →</a>
            </div>
            <div class="p-6 space-y-5">
                @forelse ($paiementsRecents as $paiement)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-slate-50 text-slate-400 rounded-xl group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-slate-700 capitalize">{{ str_replace('_', ' ', $paiement->methode_paiement) }}</p>
                            <p class="text-[10px] text-slate-400">{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d M Y, H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-emerald-600">+{{ number_format($paiement->montant, 0, ',', ' ') }} F</p>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fa-regular fa-credit-card text-3xl text-slate-200 mb-3 block"></i>
                    <p class="text-sm text-slate-400">Aucune transaction récente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js — Données réelles depuis l'API -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';

    let mainChartInstance = null;

    // ── Graphique principal : Réservations + Revenus (données réelles) ──
    const mainCtx = document.getElementById('mainChart');
    if (mainCtx) {
        const ctx = mainCtx.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(225, 29, 72, 0.15)');
        gradient.addColorStop(1, 'rgba(225, 29, 72, 0)');

        const gradient2 = ctx.createLinearGradient(0, 0, 0, 350);
        gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
        gradient2.addColorStop(1, 'rgba(16, 185, 129, 0)');

        mainChartInstance = new Chart(mainCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Réservations',
                        data: [],
                        borderColor: '#e11d48',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#e11d48',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradient,
                        hidden: false,
                    },
                    {
                        label: 'Revenus (FCFA)',
                        data: [],
                        borderColor: '#10b981',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradient2,
                        hidden: true,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 14,
                        cornerRadius: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.datasetIndex === 1) {
                                    return ctx.dataset.label + ': ' + new Intl.NumberFormat('fr-FR').format(ctx.parsed.y) + ' F';
                                }
                                return ctx.dataset.label + ': ' + ctx.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(241, 245, 249, 0.8)', drawBorder: false },
                        ticks: { padding: 12, stepSize: 1 }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        display: false,
                        grid: { display: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { padding: 12 }
                    }
                }
            }
        });

        // Charger les données réelles depuis l'API
        fetch('/admin/api/chart-stats')
            .then(r => r.json())
            .then(data => {
                mainChartInstance.data.labels = data.labels;
                mainChartInstance.data.datasets[0].data = data.reservations;
                mainChartInstance.data.datasets[1].data = data.revenus;
                mainChartInstance.update();
            })
            .catch(err => console.error('Erreur chargement stats:', err));
    }

    // Toggle entre Réservations et Revenus
    window.toggleDataset = function(index) {
        if (!mainChartInstance) return;
        mainChartInstance.data.datasets.forEach((ds, i) => {
            ds.hidden = (i !== index);
        });
        // Afficher le bon axe Y
        mainChartInstance.options.scales.y.display = (index === 0);
        mainChartInstance.options.scales.y1.display = (index === 1);
        mainChartInstance.update();
    };

    // ── Graphique Doughnut : Taux d'occupation (données réelles) ──
    const occCtx = document.getElementById('occupancyChart');
    if (occCtx) {
        new Chart(occCtx, {
            type: 'doughnut',
            data: {
                labels: ['Occupé', 'Disponible'],
                datasets: [{
                    data: [{{ $stats['taux_occupation'] }}, {{ 100 - $stats['taux_occupation'] }}],
                    backgroundColor: ['#e11d48', '#f1f5f9'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                cutout: '78%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endsection