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
        'id_location_vehicule',
        'id_commande_service',
        'montant',
        'montant_rembourse',
        'date_paiement',
        'methode_paiement',
        'reference_transaction',
        'statut',
        'id_admin_validation',
        'preuve_paiement',
        'notes'
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'montant' => 'decimal:2',
        'montant_rembourse' => 'decimal:2',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }

    public function demandeVisite(): BelongsTo
    {
        return $this->belongsTo(DemandeVisite::class, 'id_demande_visite');
    }

    public function locationVehicule(): BelongsTo
    {
        return $this->belongsTo(LocationVehicule::class, 'id_location_vehicule');
    }

    public function commandeService(): BelongsTo
    {
        return $this->belongsTo(CommandeService::class, 'id_commande_service');
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin_validation');
    }
}