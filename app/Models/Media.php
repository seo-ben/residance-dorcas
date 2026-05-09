<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMedia
 */
class Media extends Model
{
    use HasFactory;
    protected $table = 'medias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_reference', 'type_reference', 'type_media', 'titre',
        'description', 'chemin_fichier', 'est_couverture', 'ordre', 'date_ajout'
    ];
}
