<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperAvi
 */
class Avi extends Model
{
    use HasFactory;
    protected $table = 'avis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_client',
        'id_chambre', // Add this line
        'id_reservation',
        'note_globale',
        'note_proprete',
        'note_service',
        'note_emplacement',
        'commentaire',
        'date_avis',
        'statut',
        'reponse_admin',
        'date_reponse',
        'id_admin_reponse'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class, 'id_admin_reponse');
    }
}
