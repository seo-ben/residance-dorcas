<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperParametreSysteme
 */
class ParametreSysteme extends Model
{
    use HasFactory;
    protected $table = 'parametres_systeme';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cle', 'valeur', 'description', 'type_parametre',
        'modifiable', 'date_modification', 'id_admin_modification'
    ];

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class, 'id_admin_modification');
    }
}
