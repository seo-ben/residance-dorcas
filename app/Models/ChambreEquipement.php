<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperChambreEquipement
 */
class ChambreEquipement extends Model
{ 
    use HasFactory;

    protected $table = 'chambre_equipements';
    protected $primaryKey = ['id_chambre', 'id_equipement'];
    public $incrementing = false;
    protected $fillable = ['id_chambre', 'id_equipement', 'quantite', 'notes'];

    public function chambre(): BelongsTo
    {
        return $this->belongsTo(Chambre::class, 'id_chambre');
    }

    public function equipement(): BelongsTo
    {
        return $this->belongsTo(Equipement::class, 'id');
    }
}