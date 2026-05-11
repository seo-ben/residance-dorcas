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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
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
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $order->prix_total_format }}</div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->statut_paiement == 'paye' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->statut_paiement == 'non_paye' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $order->statut_paiement == 'rembourse' ? 'bg-gray-100 text-gray-800' : '' }}
                            ">
                                {{ $order->statut_paiement == 'paye' ? 'Payé' : ($order->statut_paiement == 'rembourse' ? 'Remboursé' : 'Non payé') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-red-600 hover:text-red-900 font-bold flex items-center justify-end w-full">
                                Gérer
                                <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            
                            <div x-show="open" 
                                @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                class="absolute right-0 mt-2 w-64 rounded-2xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden text-left border border-gray-100">
                                <form action="{{ route('admin.services.update-order-status', $order) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-3 border-b border-gray-50 bg-gray-50/50">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Statut Commande</p>
                                        <div class="grid grid-cols-2 gap-1">
                                            <button type="submit" name="statut" value="confirmee" class="px-2 py-1.5 text-left text-[11px] font-bold hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">Confirmer</button>
                                            <button type="submit" name="statut" value="en_cours" class="px-2 py-1.5 text-left text-[11px] font-bold hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors">En cours</button>
                                            <button type="submit" name="statut" value="terminee" class="px-2 py-1.5 text-left text-[11px] font-bold hover:bg-green-50 hover:text-green-600 rounded-lg transition-colors text-green-600">Terminer</button>
                                            <button type="submit" name="statut" value="annulee" class="px-2 py-1.5 text-left text-[11px] font-bold hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors text-red-500">Annuler</button>
                                        </div>
                                    </div>

                                    <div class="p-3 border-b border-gray-50">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Paiement ({{ $order->prix_total_format }})</p>
                                        <input type="hidden" name="statut" value="{{ $order->statut }}">
                                        <div class="space-y-2">
                                            <select name="statut_paiement" class="w-full text-xs p-2 rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500">
                                                <option value="non_paye" {{ $order->statut_paiement == 'non_paye' ? 'selected' : '' }}>Non payé</option>
                                                <option value="paye" {{ $order->statut_paiement == 'paye' ? 'selected' : '' }}>Payé (Encaisser)</option>
                                                <option value="rembourse" {{ $order->statut_paiement == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                                            </select>
                                            
                                            <select name="methode_paiement" class="w-full text-xs p-2 rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500">
                                                <option value="especes">Espèces</option>
                                                <option value="virement">Virement</option>
                                                <option value="mobile_money">Mobile Money</option>
                                                <option value="carte_credit">Carte Crédit</option>
                                                <option value="autre">Autre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-gray-50/30">
                                        <textarea name="notes_admin" placeholder="Note admin..." class="w-full text-xs p-2 rounded-xl border-gray-200 focus:ring-red-500 focus:border-red-500 min-h-[60px]">{{ $order->notes_admin }}</textarea>
                                        <button type="submit" class="mt-2 w-full bg-red-600 text-white text-[11px] font-bold py-2 rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-100">
                                            Mettre à jour
                                        </button>
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
