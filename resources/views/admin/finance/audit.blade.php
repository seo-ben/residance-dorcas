@extends('layouts.playout')

@section('title', 'Audit financier')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Audit financier</h1>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Filtres</h2>
            <form method="GET" action="{{ route('admin.finance.audit') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

        <!-- Tableau des audits -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Historique des audits</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modèle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($audits as $audit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $audit->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $audit->user ? $audit->user->name : 'Système' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($audit->action === 'refund')
                                    Remboursement
                                @elseif($audit->action === 'payment_processed')
                                    Paiement traité
                                @elseif($audit->action === 'status_updated')
                                    Mise à jour du statut
                                @else
                                    {{ ucfirst($audit->action) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $audit->model_type }} #{{ $audit->model_id }}</td>
                            <td class="px-6 py-4">
                                <pre class="text-sm text-gray-600">{{ json_encode(json_decode($audit->details), JSON_PRETTY_PRINT) }}</pre>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($audit->action)
                                    @case('payment_session_created')
                                        Création de session de paiement
                                        @break
                                    @case('payment_processed')
                                        Paiement traité
                                        @break
                                    @case('payment_failed')
                                        Paiement échoué
                                        @break
                                    @case('reservation_cancelled')
                                        Annulation de réservation
                                        @break
                                    @case('reservation_created')
                                        Création de réservation
                                        @break
                                    @case('reservation_updated')
                                        Mise à jour de réservation
                                        @break
                                    @case('reservation_draft_saved')
                                        Réservation sauvegardée en brouillon
                                        @break
                                    @case('reservation_payment_pending')
                                        Réservation en attente de paiement
                                        @break
                                    @case('session_expired')
                                        Session de paiement expirée
                                        @break
                                    @default
                                        {{ ucfirst($audit->action) }}
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">
                {{ $audits->links() }}
            </div>
        </div>
    </div>
@endsection