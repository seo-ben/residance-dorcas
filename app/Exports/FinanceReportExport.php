<?php

namespace App\Exports;

use App\Models\Paiement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auditable;
class FinanceReportExport implements FromCollection, WithHeadings
{
    
    protected $dateDebut;
    protected $dateFin;

    public function __construct($dateDebut, $dateFin)
    {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    public function collection()
    {
        return Paiement::with(['reservation.client.user', 'reservation.details.chambre.propriete'])
            ->whereBetween('date_paiement', [$this->dateDebut, $this->dateFin])
            ->where('statut', 'valide')
            ->get()
            ->map(function ($paiement) {
                return [
                    'Reference' => $paiement->reservation->reference,
                    'Client' => $paiement->reservation->client->user->name,
                    'Propriete' => $paiement->reservation->details->first()->chambre->propriete->nom,
                    'Montant' => number_format($paiement->montant, 0, ',', ' '),
                    'Date' => $paiement->date_paiement,
                    'Methode' => $paiement->methode_paiement,
                    'Statut' => $paiement->statut,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Référence Réservation',
            'Client',
            'Propriété',
            'Montant (F CFA)',
            'Date de Paiement',
            'Méthode de Paiement',
            'Stat rheumatoid',
        ];

    }

}