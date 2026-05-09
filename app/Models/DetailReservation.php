<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDetailReservation
 */
class DetailReservation extends Model
{
    use HasFactory;
    protected $table = 'details_reservation';
    protected $primaryKey = 'id';
    protected $fillable = ['id_reservation', 'id_chambre', 'prix_unitaire', 'quantite', 'notes'];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class, 'id_chambre');
    }
}
