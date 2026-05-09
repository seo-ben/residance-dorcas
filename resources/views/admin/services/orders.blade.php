@extends('layouts.playout')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Commandes de Services</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résidence</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service(s)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->date_commande)->format('d/m/Y H:i') }}</div>
                            <div class="text-xs text-gray-500">Prévu pour : {{ \Carbon\Carbon::parse($order->date_service_souhaitee)->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->client->user->name ?? 'Client inconnu' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->client->user->email ?? '' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->client->user->telephone ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->id_reservation)
                                <a href="{{ route('admin.reservations.show', $order->id_reservation) }}" class="text-sm text-red-600 hover:underline">
                                    Réservation #{{ $order->reservation->reference }}
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Service externe</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @foreach($order->details as $detail)
                                <div class="text-sm text-gray-900">
                                    <span class="font-bold">{{ $detail->quantite }}x</span> {{ $detail->service->nom }}
                                </div>
                            @endforeach
                            @if($order->notes_client)
                                <div class="text-xs text-gray-500 italic mt-1 bg-yellow-50 p-2 rounded border border-yellow-100">
                                    "{{ $order->notes_client }}"
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->statut == 'terminee' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->statut == 'confirmee' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->statut == 'en_cours' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                {{ $order->statut == 'annulee' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucfirst(str_replace('_', ' ', $order->statut)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-red-600 hover:text-red-900 font-bold flex items-center justify-end w-full">
                                Gérer
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden">
                                <form action="{{ route('admin.services.update-order-status', $order) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-2 space-y-1">
                                        <button type="submit" name="statut" value="confirmee" class="w-full text-left px-4 py-2 text-xs hover:bg-gray-100 rounded">Confirmer</button>
                                        <button type="submit" name="statut" value="en_cours" class="w-full text-left px-4 py-2 text-xs hover:bg-gray-100 rounded">En cours</button>
                                        <button type="submit" name="statut" value="terminee" class="w-full text-left px-4 py-2 text-xs hover:bg-gray-100 rounded text-green-600 font-bold">Terminer</button>
                                        <button type="submit" name="statut" value="annulee" class="w-full text-left px-4 py-2 text-xs hover:bg-gray-100 rounded text-red-600">Annuler</button>
                                    </div>
                                    <div class="p-2 border-t bg-gray-50">
                                        <textarea name="notes_admin" placeholder="Note admin..." class="w-full text-xs p-1 rounded border-gray-300">{{ $order->notes_admin }}</textarea>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection
