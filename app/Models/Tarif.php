<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTarif
 */
class Tarif extends Model
{
    use HasFactory;
    protected $table = 'tarifs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_chambre', 'date_debut', 'date_fin', 'prix', 'type_tarif',
        'pourcentage_reduction', 'minimum_nuits', 'notes'
    ];

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class, 'id_chambre');
    }
}
