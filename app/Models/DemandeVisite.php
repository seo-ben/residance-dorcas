<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperDemandeVisite
 */
class DemandeVisite extends Model
{
    use HasFactory;
    protected $table = 'demandes_visite';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_client', 'id_chambre', 'date_demande', 'date_visite_souhaitee',
        'message', 'statut', 'date_confirmation', 'id_admin_confirmation', 'notes_admin'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class, 'id_chambre');
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin_confirmation');
    }

    public function reservation(): HasOne
    {
        return $this->hasOne(Reservation::class, 'id_demande_visite');
    }
}
