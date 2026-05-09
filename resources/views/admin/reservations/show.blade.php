@extends('layouts.playout')

@section('title', 'Détails de la Réservation')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Retour aux réservations
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Reservation Details -->
        <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Réservation #{{ $reservation->reference }}</h1>
                    @php
                        $statusClasses = [
                            'en_attente_paiement' => 'bg-yellow-100 text-yellow-800',
                            'acompte_paye' => 'bg-blue-100 text-blue-800',
                            'confirmee' => 'bg-green-100 text-green-800',
                            'annulee' => 'bg-red-100 text-red-800',
                            'terminee' => 'bg-gray-100 text-gray-800'
                        ];
                        $statusLabels = [
                            'en_attente_paiement' => 'En attente de paiement',
                            'acompte_paye' => 'Acompte payé',
                            'confirmee' => 'Confirmée',
                            'annulee' => 'Annulée',
                            'terminee' => 'Terminée'
                        ];
                        $statusClass = $statusClasses[$reservation->statut] ?? 'bg-gray-100 text-gray-800';
                        $statusLabel = $statusLabels[$reservation->statut] ?? $reservation->statut;
                    @endphp
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Client Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Informations client</h2>
                        <div class="space-y-2">
                            <p><span class="font-medium">Nom:</span> {{ $reservation->client->user->name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $reservation->client->user->email }}</p>
                            <p><span class="font-medium">Tel:</span> {{ $reservation->client->user->telephone }}</p>
                        </div>
                    </div>

                    <!-- Reservation Details -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Détails de la réservation</h2>
                        <div class="space-y-2">
                            <p><span class="font-medium">Date de création:</span> {{ \Carbon\Carbon::parse($reservation->created_at)->format('d/m/Y H:i') }}</p>
                            <p><span class="font-medium">Arrivée:</span> {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}</p>
                            <p><span class="font-medium">Départ:</span> {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}</p>
                            <p><span class="font-medium">Durée:</span> {{ \Carbon\Carbon::parse($reservation->date_arrivee)->diffInDays(\Carbon\Carbon::parse($reservation->date_depart)) }} nuits</p>
                            <p><span class="font-medium">Prix total:</span> {{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Notes</h2>
                        <div class="space-y-2">
                            <p><span class="font-medium">Notes client:</span> {{ $reservation->notes_client ?: 'Aucune note' }}</p>
                            <p><span class="font-medium">Notes admin:</span> {{ $reservation->notes_admin ?: 'Aucune note' }}</p>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Code QR</h2>
                        @if($estPayee && isset($qrCodeBase64) && $reservation->statut !== 'annulee')
                            <div class="text-center">
                                <p class="text-sm text-gray-600 mb-4">Ce QR code peut être utilisé pour vérifier la réservation.</p>
                                <div class="inline-block p-3 bg-white border-2 border-red-100 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <img src="{{ $qrCodeBase64 }}" style="max-width: 50%;" alt="QR Code de réservation" class="mx-auto">
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500">Aucun QR code disponible (réservation non payée ou annulée).</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Details -->
        <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">appartement réservées</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chambre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriété</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Équipements</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reservation->details as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($detail->chambre->medias->first())
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                         src="{{ Storage::url($detail->chambre->medias->first()->chemin_fichier) }}"
                                                         alt="Image de la chambre">
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $detail->chambre->numero_chambre }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $detail->chambre->propriete->nom }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($detail->chambre->equipements->isNotEmpty())
                                                {{ $detail->chambre->equipements->pluck('nom')->implode(', ') }}
                                            @else
                                                Aucun équipement
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($detail->prix_unitaire, 0, ',', ' ') }} FCFA</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Historique des paiements</h2>
                @if($reservation->paiements->isEmpty())
                    <p class="text-gray-500">Aucun paiement enregistré pour cette réservation.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reservation->paiements as $paiement)
                                    @php
                                        $paiementStatusClasses = [
                                            'valide' => 'bg-green-100 text-green-800',
                                            'en_attente' => 'bg-yellow-100 text-yellow-800',
                                            'refuse' => 'bg-red-100 text-red-800',
                                            'rembourse' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $paiementStatusLabels = [
                                            'valide' => 'Validé',
                                            'en_attente' => 'En attente',
                                            'refuse' => 'Refusé',
                                            'rembourse' => 'Remboursé'
                                        ];
                                        $paiementStatusClass = $paiementStatusClasses[$paiement->statut] ?? 'bg-gray-100 text-gray-800';
                                        $paiementStatusLabel = $paiementStatusLabels[$paiement->statut] ?? $paiement->statut;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paiementStatusClass }}">
                                                {{ $paiementStatusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $paiement->reference_transaction ?: 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($reservation->statut != 'terminee' && $paiement->statut == 'valide')
                                                <form action="{{ route('admin.paiements.refund', [$paiement->id, $reservation->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="montant" value="{{ $paiement->montant }}">
                                                    <input type="hidden" name="raison" value="Remboursement demandé par l'admin">
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Confirmer le remboursement ?')">Rembourser</button>
                                                </form>
                                            @elseif($reservation->statut == 'terminee')
                                                <span class="text-gray-500">Aucune action disponible</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Payment Summary -->
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Résumé des paiements</h3>
                        <p><span class="font-medium">Prix total:</span> {{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</p>
                        <p><span class="font-medium">Total payé:</span> {{ number_format($reservation->paiements->where('statut', 'valide')->sum('montant'), 0, ',', ' ') }} FCFA</p>
                        <p><span class="font-medium">Reste à payer:</span> {{ number_format($reservation->prix_total - $reservation->paiements->where('statut', 'valide')->sum('montant'), 0, ',', ' ') }} FCFA</p>
                        @if($reservation->statut == 'terminee')
                            <p class="text-sm text-gray-600 mt-2">La réservation est terminée. Aucun remboursement ou paiement supplémentaire n'est possible.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Communication History -->
        <div class="bg-white overflow-hidden shadow-xl rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Historique des communications</h2>
                    <a href="{{ route('admin.reservations.communications', $reservation->id) }}" class="text-red-600 hover:text-red-900">Voir tout l'historique</a>
                </div>
                @if($communications->isEmpty())
                    <p class="text-gray-500">Aucune communication enregistrée pour cette réservation.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Envoyé par</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($communications->take(5) as $communication)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($communication->sent_at)->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $communication->type }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ Str::limit($communication->message, 100) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $communication->admin_id ? 'Admin #' . $communication->admin_id : 'Système' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Update Status Form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Mettre à jour le statut</h2>
                    <form action="{{ route('admin.reservations.update-status', $reservation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select id="statut" name="statut" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                <option value="en_attente_paiement" {{ $reservation->statut == 'en_attente_paiement' ? 'selected' : '' }}>En attente de paiement</option>
                                <option value="acompte_paye" {{ $reservation->statut == 'acompte_paye' ? 'selected' : '' }}>Acompte payé</option>
                                <option value="confirmee" {{ $reservation->statut == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                <option value="annulee" {{ $reservation->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                <option value="terminee" {{ $reservation->statut == 'terminee' ? 'selected' : '' }}>Terminée</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="notes_admin" class="block text-sm font-medium text-gray-700">Notes administratives</label>
                            <textarea id="notes_admin" name="notes_admin" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md">{{ $reservation->notes_admin }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Manual Payment Form -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Enregistrer un paiement</h2>
                    <form action="{{ route('admin.reservations.store-payment', $reservation->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="montant" class="block text-sm font-medium text-gray-700">Montant (FCFA)</label>
                                <input type="number" id="montant" name="montant" value="{{ $reservation->prix_total - $reservation->acompte_paye }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" required>
                            </div>
                            <div>
                                <label for="methode_paiement" class="block text-sm font-medium text-gray-700">Méthode</label>
                                <select id="methode_paiement" name="methode_paiement" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                    <option value="especes">Espèces</option>
                                    <option value="virement">Virement Bancaire</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="carte_credit">Carte de crédit</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="reference_transaction" class="block text-sm font-medium text-gray-700">Référence (Optionnel)</label>
                            <input type="text" id="reference_transaction" name="reference_transaction" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Ex: TRX-12345">
                        </div>
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Interne)</label>
                            <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Détails sur le paiement..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                Enregistrer le paiement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notify Client Form -->
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Notifier le client</h2>
                <form action="{{ route('admin.reservations.notifyClient', $reservation->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Type de notification</label>
                        <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea id="message" name="message" rows="4" class="shadow-sm focus:ring-red-500 focus:border-red-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                            Envoyer la notification
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection