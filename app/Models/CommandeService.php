<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCommandeService
 */
class CommandeService extends Model
{
    use HasFactory;
    protected $table = 'commandes_services';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_reservation', 'id_client', 'date_commande', 'date_service_souhaitee',
        'statut', 'notes_client', 'notes_admin'
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailCommandeService::class, 'id_commande_service');
    }
}
