<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque',
        'modele',
        'immatriculation',
        'type',
        'transmission',
        'carburant',
        'nb_places',
        'prix_journalier',
        'description',
        'statut',
        'caracteristiques',
    ];

    protected $casts = [
        'caracteristiques' => 'array',
        'prix_journalier' => 'decimal:2',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(VehiculeImage::class, 'id_vehicule');
    }

    public function primaryImage()
    {
        return $this->hasOne(VehiculeImage::class, 'id_vehicule')->where('est_principale', true);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(LocationVehicule::class, 'id_vehicule');
    }
}
