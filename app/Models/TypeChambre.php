<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperTypeChambre
 */
class TypeChambre extends Model
{
    use HasFactory;
    protected $table = 'types_appartement';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nom',
        'description',
        'capacite_standard',
        'capacite_max',
        'superficie',
        'etage_type'
    ];

    public function appartement(): HasMany
    {
        return $this->hasMany(Chambre::class, 'id_type_chambre');
    }

    public function medias(): HasMany
    {
        return $this->hasMany(Media::class, 'id_reference')->where('type_reference', 'type_chambre');
    }
}
