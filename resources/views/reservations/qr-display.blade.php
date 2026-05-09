<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Réservation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: white;
            color: #333;
            padding: 20px;
            line-height: 1.5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .status {
            font-weight: bold;
            margin-top: 10px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .info-label {
            font-weight: normal;
        }

        .info-value {
            font-weight: bold;
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 480px) {
            .info-row {
                flex-direction: column;
                gap: 3px;
            }
            
            .info-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Détails de la Réservation</h1>
            <div class="status">Statut: {{ ucfirst($reservation->statut) }}</div>
        </div>

        <!-- Section Référence -->
        <div class="section">
            <div class="section-title">Référence de Réservation</div>
            <div class="info-row">
                <span class="info-label">Référence:</span>
                <span class="info-value">{{ $reservation->reference }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Code de confirmation:</span>
                <span class="info-value">{{ $reservation->reference }}</span>
            </div>
        </div>

        <!-- Section Client -->
        <div class="section">
            <div class="section-title">Informations Client</div>
            <div class="info-row">
                <span class="info-label">Nom:</span>
                <span class="info-value">{{ $reservation->client->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $reservation->client->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone:</span>
                <span class="info-value">{{ $reservation->client->user->telephone ?? 'Non renseigné' }}</span>
            </div>
        </div>
        <!-- Section Séjour -->
        <div class="section">
            <div class="section-title">Détails du Séjour</div>
            <div class="info-row">
                <span class="info-label">Date d'arrivée:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date de départ:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Durée:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($reservation->date_arrivee)->diffInDays(\Carbon\Carbon::parse($reservation->date_depart)) }} nuit(s)</span>
            </div>
        </div>

        <!-- Section Hébergement -->
        @if($reservation->details->first())
        <div class="section">
            <div class="section-title">Hébergement</div>
            <div class="info-row">
                <span class="info-label">Propriété:</span>
                <span class="info-value">{{ optional($reservation->details->first()->chambre->propriete)->nom }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Chambre:</span>
                <span class="info-value">{{ optional($reservation->details->first()->chambre)->numero_chambre }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Type:</span>
                <span class="info-value">{{ optional($reservation->details->first()->chambre->typeChambre)->nom }}</span>
            </div>
        </div>
        @endif

        <!-- Section Paiement -->
        <div class="section">
            <div class="section-title">Informations de Paiement</div>
            <div class="info-row">
                <span class="info-label">Montant total:</span>
                <span class="info-value">{{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($paiement)
            <div class="info-row">
                <span class="info-label">Montant payé:</span>
                <span class="info-value">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date de paiement:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut:</span>
                <span class="info-value">{{ ucfirst($paiement->statut) }}</span>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>Réservation vérifiée et confirmée</p>
            <p>Consulté le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>