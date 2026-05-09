@extends('layouts.playout')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="p-6" x-data="{ createModalOpen: false }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Utilisateurs</h1>
            <p class="text-gray-500 font-medium">Gérez les comptes clients, administrateurs et membres de l'équipe</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="createModalOpen = true" class="bg-gray-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-gray-200 hover:bg-black transition-all flex items-center gap-2">
                <i class="fas fa-user-plus"></i> Nouvel Utilisateur
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Utilisateurs</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-shield text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Administrateurs</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['admins'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-tag text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Clients</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['clients'] }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-plus text-indigo-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Nouveaux (Mois)</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $stats['new_this_month'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @include('components.alerts')

    <!-- Filtres & Recherche -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.users') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[300px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, email ou téléphone..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-500/20 transition-all">
            </div>
            <button type="submit" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                Rechercher
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users') }}" class="text-sm font-bold text-red-600 hover:underline">Effacer</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date Inscription</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors" x-data="{ editModalOpen: false }">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-bold border border-gray-100 overflow-hidden shadow-sm">
                                    @if($user->profile_photo_url)
                                        <img src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->type_utilisateur == 'admin')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-50 text-red-600 border border-red-100">
                                    <i class="fas fa-shield-alt"></i> Administrateur
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-green-50 text-green-600 border border-green-100">
                                    <i class="fas fa-user"></i> Client
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-700">{{ $user->telephone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">{{ $user->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-gray-400 font-bold">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <button @click="editModalOpen = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all border border-gray-50" title="Modifier">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all border border-gray-50" title="Supprimer">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Modal de modification -->
                            <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="editModalOpen" @click="editModalOpen = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    <div x-show="editModalOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                            Modifier {{ $user->name }}
                                                        </h3>
                                                        
                                                        @if ($errors->any())
                                                            <div class="mt-2 bg-red-50 text-red-600 p-3 rounded-lg text-xs font-medium text-left">
                                                                <ul class="list-disc pl-4 space-y-1">
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                        <div class="mt-4 space-y-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Rôle</label>
                                                                <select name="type_utilisateur" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                                                    <option value="client" {{ $user->type_utilisateur == 'client' ? 'selected' : '' }}>Client</option>
                                                                    <option value="admin" {{ $user->type_utilisateur == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                                                <input type="text" name="telephone" value="{{ $user->telephone }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Enregistrer
                                                </button>
                                                <button type="button" @click="editModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Annuler
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Modal de création d'utilisateur -->
    <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="createModalOpen" @click="createModalOpen = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="createModalOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    Ajouter un Nouvel Utilisateur
                                </h3>
                                
                                @if ($errors->any())
                                    <div class="mb-4 bg-red-50 text-red-600 p-3 rounded-lg text-xs font-medium text-left">
                                        <ul class="list-disc pl-4 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Nom Complet</label>
                                        <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Adresse Email</label>
                                        <input type="email" name="email" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                        <input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                        <input type="password" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                        <input type="text" name="telephone" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Rôle</label>
                                        <select name="type_utilisateur" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-xl">
                                            <option value="client">Client</option>
                                            <option value="admin">Administrateur</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-gray-900 text-base font-medium text-white hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 sm:ml-3 sm:w-auto sm:text-sm">
                            Créer l'utilisateur
                        </button>
                        <button type="button" @click="createModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
