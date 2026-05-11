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

    protected $casts = [
        'date_commande' => 'datetime',
        'date_service_souhaitee' => 'datetime',
    ];

    protected $appends = ['date_service_format', 'heure_service_format', 'prix_total', 'prix_total_format'];

    public function getDateServiceFormatAttribute()
    {
        return $this->date_service_souhaitee ? $this->date_service_souhaitee->format('d/m/Y') : null;
    }

    public function getHeureServiceFormatAttribute()
    {
        return $this->date_service_souhaitee ? $this->date_service_souhaitee->format('H:i') : null;
    }

    public function getPrixTotalAttribute()
    {
        return $this->details->sum(function($detail) {
            return $detail->prix_unitaire * $detail->quantite;
        });
    }

    public function getPrixTotalFormatAttribute()
    {
        return number_format($this->getPrixTotalAttribute(), 0, ',', ' ') . ' FCFA';
    }

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
