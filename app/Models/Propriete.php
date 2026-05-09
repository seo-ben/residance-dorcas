<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPropriete
 */
class Propriete extends Model
{
    use HasFactory;
    protected $table = 'proprietes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'pays',
        'code_postal',
        'telephone',
        'email',
        'description',
        'etoiles',
        'latitude',
        'longitude',
        'statut',
        'date_ajout'
    ];

    public function appartement(): HasMany
    {
        return $this->hasMany(Chambre::class, 'id_propriete');
    }

    public function medias(): HasMany
    {
        return $this->hasMany(Media::class, 'id_reference')->where('type_reference', 'propriete');
    }
}
