@extends('layouts.playout')

@section('title', 'Historique Véhicule - ' . $vehicule->marque . ' ' . $vehicule->modele)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.vehicules.index') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-primary-600 hover:border-primary-100 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                    {{ $vehicule->marque }} <span class="text-primary-600">{{ $vehicule->modele }}</span>
                </h1>
                <p class="text-gray-500 font-medium">Immatriculation : <span class="text-gray-900 font-bold uppercase">{{ $vehicule->immatriculation }}</span></p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            @php
                $statusColors = [
                    'disponible' => 'bg-green-100 text-green-700',
                    'loue' => 'bg-blue-100 text-blue-700',
                    'maintenance' => 'bg-orange-100 text-orange-700',
                    'indisponible' => 'bg-red-100 text-red-700',
                ];
            @endphp
            <span class="px-4 py-2 {{ $statusColors[$vehicule->statut] ?? 'bg-gray-100' }} rounded-xl text-xs font-black uppercase tracking-wider shadow-sm">
                {{ ucfirst($vehicule->statut) }}
            </span>
            <a href="{{ route('admin.vehicules.edit', $vehicule) }}" class="px-6 py-2 bg-primary-600 text-white font-black rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-100">
                Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Vehicle Details Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden">
                <div class="relative h-48 bg-gray-100">
                    @if($vehicule->primaryImage)
                        <img src="{{ Storage::url($vehicule->primaryImage->chemin_image) }}" class="w-full h-full object-cover" alt="Vehicule">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="fas fa-car text-5xl"></i>
                        </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-black text-gray-900 mb-4">Informations Techniques</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Type</span>
                            <span class="text-gray-900 font-black">{{ ucfirst($vehicule->type) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Transmission</span>
                            <span class="text-gray-900 font-black">{{ ucfirst($vehicule->transmission) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Carburant</span>
                            <span class="text-gray-900 font-black">{{ ucfirst($vehicule->carburant) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Places</span>
                            <span class="text-gray-900 font-black">{{ $vehicule->nb_places }} places</span>
                        </div>
                        <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
                            <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Prix/Jour</span>
                            <span class="text-xl font-black text-primary-600">{{ number_format($vehicule->prix_journalier, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photos Gallery -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 border border-gray-100 p-6">
                <h3 class="text-lg font-black text-gray-900 mb-4">Galerie Photos</h3>
                <div class="grid grid-cols-3 gap-2">
                    @foreach($vehicule->images as $img)
                        <div class="aspect-square rounded-xl overflow-hidden bg-gray-50 border border-gray-100">
                            <img src="{{ Storage::url($img->chemin_image) }}" class="w-full h-full object-cover" alt="Vehicule image">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- History / Rentals Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">Historique des locations</h2>
                        <p class="text-gray-400 font-medium">Liste de tous les clients ayant loué ce véhicule.</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 shadow-inner">
                        <i class="fas fa-history text-xl"></i>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Client</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Période</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Statut</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Revenus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($vehicule->locations as $loc)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mr-3 font-black text-xs">
                                                {{ substr($loc->client->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-900">{{ $loc->client->user->name }} {{ $loc->client->user->prenom }}</p>
                                                <p class="text-[10px] text-gray-400 font-bold">#{{ $loc->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-bold text-gray-600">
                                            {{ \Carbon\Carbon::parse($loc->date_debut)->format('d/m/Y') }} 
                                            <i class="fas fa-arrow-right text-[8px] mx-1 text-gray-300"></i>
                                            {{ \Carbon\Carbon::parse($loc->date_fin)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 font-black">
                                            {{ \Carbon\Carbon::parse($loc->date_debut)->diffInDays(\Carbon\Carbon::parse($loc->date_fin)) }} jours
                                        </p>
                                    </td>
                                    <td class="px-8 py-5">
                                        @php
                                            $locStatusColors = [
                                                'en_attente' => 'bg-yellow-100 text-yellow-700',
                                                'confirmee' => 'bg-green-100 text-green-700',
                                                'en_cours' => 'bg-blue-100 text-blue-700',
                                                'terminee' => 'bg-gray-100 text-gray-700',
                                                'annulee' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 {{ $locStatusColors[$loc->statut] ?? 'bg-gray-100' }} rounded-lg text-[9px] font-black uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $loc->statut) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-black text-gray-900">{{ number_format($loc->prix_total, 0, ',', ' ') }} FCFA</p>
                                        <p class="text-[10px] {{ $loc->statut_paiement === 'paye' ? 'text-green-500' : 'text-red-400' }} font-black uppercase">
                                            {{ str_replace('_', ' ', $loc->statut_paiement) }}
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-gray-400">
                                        <div class="mb-4">
                                            <i class="fas fa-calendar-times text-3xl opacity-20"></i>
                                        </div>
                                        <p class="text-sm font-medium italic">Aucune location enregistrée pour ce véhicule.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
