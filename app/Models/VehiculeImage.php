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

    protected $appends = ['url_image'];

    public function getUrlImageAttribute()
    {
        if (!$this->chemin_image) return null;
        if (filter_var($this->chemin_image, FILTER_VALIDATE_URL)) {
            return $this->chemin_image;
        }
        return asset('storage/' . $this->chemin_image);
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class, 'id_vehicule');
    }
}
