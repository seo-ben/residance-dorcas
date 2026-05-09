<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperEquipement
 */
class Equipement extends Model
{
    use HasFactory;
    protected $table = 'equipements';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'description', 'icone'];

    public function appartement(): BelongsToMany
    {
        return $this->belongsToMany(Chambre::class, 'chambre_equipements', 'id_equipement', 'id_chambre')
            ->withPivot('quantite', 'notes');
    }
}
