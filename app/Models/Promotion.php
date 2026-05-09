<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPromotion
 */
class Promotion extends Model
{
    use HasFactory;
    protected $table = 'promotions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'titre', 'description', 'code_promo', 'type_reduction',
        'valeur_reduction', 'date_debut', 'date_fin', 'conditions',
        'limite_utilisation', 'nb_utilisations', 'statut'
    ];
}
