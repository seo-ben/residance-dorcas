<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperService
 */
class Service extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nom', 'description', 'prix', 'duree_estimee', 'disponibilite',
        'horaires_debut', 'horaires_fin', 'statut'
    ];

    public function detailsCommandes(): HasMany
    {
        return $this->hasMany(DetailCommandeService::class, 'id_service');
    }
}
