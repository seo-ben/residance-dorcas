<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationVehicule extends Model
{
    use HasFactory;

    protected $table = 'locations_vehicules';

    protected $fillable = [
        'id_vehicule',
        'id_client',
        'id_reservation',
        'date_debut',
        'date_fin',
        'prix_total',
        'statut',
        'statut_paiement',
        'notes',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'prix_total' => 'decimal:2',
    ];

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class, 'id_vehicule');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }
}
