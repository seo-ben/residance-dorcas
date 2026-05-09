<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_reservation',
        'id_demande_visite',
        'montant',
        'montant_rembourse',
        'date_paiement',
        'methode_paiement',
        'reference_transaction',
        'statut',
        'id_admin_validation',
        'notes'
    ];

    protected $dates = [
        'date_paiement'
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }

    public function demandeVisite(): BelongsTo
    {
        return $this->belongsTo(DemandeVisite::class, 'id_demande_visite');
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class, 'id_admin_validation');
    }
}