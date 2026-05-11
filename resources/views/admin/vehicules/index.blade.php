@extends('layouts.playout')

@section('title', 'Gestion des Véhicules')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <header class="mb-8 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900">Parc Automobile</h1>
                    <p class="text-gray-500 mt-1">Gérez votre flotte de véhicules et les locations en cours.</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.vehicules.rentals') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                        <i class="fas fa-list mr-2"></i>
                        Voir les Locations
                    </a>
                    <a href="{{ route('admin.vehicules.create') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200">
                        <i class="fas fa-plus mr-2"></i>
                        Ajouter un Véhicule
                    </a>
                </div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-car text-xl"></i>
                    </div>
                    <span class="text-2xl font-black text-gray-900">{{ $vehicules->total() }}</span>
                </div>
                <p class="text-sm font-medium text-gray-500">Total Véhicules</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <span class="text-2xl font-black text-gray-900">{{ App\Models\Vehicule::where('statut', 'disponible')->count() }}</span>
                </div>
                <p class="text-sm font-medium text-gray-500">Disponibles</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                        <i class="fas fa-key text-xl"></i>
                    </div>
                    <span class="text-2xl font-black text-gray-900">{{ App\Models\Vehicule::where('statut', 'loue')->count() }}</span>
                </div>
                <p class="text-sm font-medium text-gray-500">En Location</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                        <i class="fas fa-tools text-xl"></i>
                    </div>
                    <span class="text-2xl font-black text-gray-900">{{ App\Models\Vehicule::where('statut', 'maintenance')->count() }}</span>
                </div>
                <p class="text-sm font-medium text-gray-500">En Maintenance</p>
            </div>
        </div>

        <!-- Vehicles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($vehicules as $vehicule)
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
                    <!-- Image -->
                    <div class="relative h-48">
                        @if($vehicule->primaryImage)
                            <img src="{{ asset('storage/' . $vehicule->primaryImage->chemin_image) }}" alt="{{ $vehicule->marque }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fas fa-car text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm
                                {{ $vehicule->statut === 'disponible' ? 'bg-green-100 text-green-700' : 
                                   ($vehicule->statut === 'loue' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                {{ $vehicule->statut }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $vehicule->marque }} {{ $vehicule->modele }}</h3>
                                <p class="text-sm text-gray-500">{{ $vehicule->immatriculation }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-black text-red-600">{{ number_format($vehicule->prix_journalier, 0, ',', ' ') }}</span>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">FCFA / JOUR</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-2 rounded-xl">
                                <i class="fas fa-cog mr-2 text-gray-400"></i>
                                {{ $vehicule->transmission }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-2 rounded-xl">
                                <i class="fas fa-gas-pump mr-2 text-gray-400"></i>
                                {{ $vehicule->carburant }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.vehicules.edit', $vehicule) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.vehicules.destroy', $vehicule) }}" method="POST" onsubmit="return confirm('Supprimer ce véhicule ?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <a href="{{ route('admin.vehicules.show', $vehicule) }}" class="text-xs font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest">
                                Historique <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $vehicules->links() }}
        </div>
    </div>
</div>
@endsection
