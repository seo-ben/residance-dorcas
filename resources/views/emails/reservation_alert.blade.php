{{-- @component('mail::message')
# Alerte de Réservation

Bonjour {{ $reservation->client->user->name }},

Il ne reste plus que **{{ $joursRestants }} jour(s)** avant la fin de votre réservation **{{ $reservation->reference }}** pour la chambre **{{ $reservation->details->first()->chambre->numero_chambre }}** à **{{ $reservation->details->first()->chambre->propriete->nom }}**.

**Détails :**

- **Date d'arrivée** : {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}
- **Date de départ** : {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}
- **Prix total** : {{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA

Veuillez vous assurer de préparer votre départ. Si vous souhaitez prolonger votre séjour, contactez-nous dès maintenant.

@component('mail::button', ['url' => route('reservations.show', $reservation->id)])
Voir les détails
@endcomponent

Merci de votre confiance,

L'équipe de réservation de la Résidence DORCAS
@endcomponent --}}




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerte de Réservation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        
        .content {
            padding: 30px;
        }
        
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .alert-box .days {
            font-size: 24px;
            font-weight: bold;
            color: #856404;
        }
        
        .details-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .details-box h3 {
            color: #495057;
            margin-top: 0;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        
        .detail-value {
            color: #6c757d;
        }
        
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .reservation-ref {
            background-color: #e7f3ff;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
            color: #0066cc;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🏨 Alerte de Réservation</h1>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p><strong>Bonjour {{ $reservation->client->user->name }},</strong></p>
            
            <div class="alert-box">
                <div class="days">{{-- $joursRestants --}} jour(s) restant(s)</div>
                <p>avant la fin de votre séjour</p>
            </div>
            
            <p>Votre réservation <span class="reservation-ref">{{ $reservation->reference }}</span> pour la chambre <strong>{{ $reservation->details->first()->chambre->numero_chambre }}</strong> à <strong>{{ $reservation->details->first()->chambre->propriete->nom }}</strong> arrive à son terme.</p>
            
            <div class="details-box">
                <h3>📋 Détails de votre réservation</h3>
                
                <div class="detail-item">
                    <span class="detail-label">📅 Date d'arrivée</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">📅 Date de départ</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">💰 Prix total</span>
                    <span class="detail-value">{{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            
            <p>Veuillez vous assurer de préparer votre départ. Si vous souhaitez prolonger votre séjour, contactez-nous dès maintenant.</p>
            
            <div class="button-container">
                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn">
                    👁️ Voir les détails
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Merci de votre confiance</strong></p>
            <p>L'équipe de réservation de la Résidence DORCAS</p>
            <p style="font-size: 12px; margin-top: 15px;">
                📧 contact@residence-dorcas.com | 📞 +228 XX XX XX XX
            </p>
        </div>
    </div>
</body>
</html>