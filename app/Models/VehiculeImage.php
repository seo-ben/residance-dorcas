<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiculeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_vehicule',
        'chemin_image',
        'est_principale',
    ];

    protected $casts = [
        'est_principale' => 'boolean',
    ];

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class, 'id_vehicule');
    }
}
