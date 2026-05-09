<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPeriodeIndisponibilite
 */
class PeriodeIndisponibilite extends Model
{
    use HasFactory;
    protected $table = 'periodes_indisponibilite';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_chambre', 'date_debut', 'date_fin', 'raison',
        'id_admin_creation', 'date_creation'
    ];

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class, 'id_chambre');
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class, 'id_admin_creation');
    }
}
