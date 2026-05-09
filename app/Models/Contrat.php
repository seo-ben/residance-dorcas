<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperContrat
 */
class Contrat extends Model
{
    use HasFactory;
    protected $table = 'contrats';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_reservation', 'reference', 'date_signature', 'date_debut',
        'date_fin', 'montant_mensuel', 'depot_garantie', 'statut',
        'fichier_contrat', 'conditions_speciales'
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }
}
