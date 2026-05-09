<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDetailCommandeService
 */
class DetailCommandeService extends Model
{
    use HasFactory;
    protected $table = 'details_commande_service';
    protected $primaryKey = 'id';
    protected $fillable = ['id_commande_service', 'id_service', 'quantite', 'prix_unitaire', 'notes'];

    public function commandeService(): BelongsTo
    {
        return $this->belongsTo(CommandeService::class, 'id_commande_service');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_service');
    }
}
