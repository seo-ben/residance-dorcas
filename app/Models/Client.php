<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperClient
 */
class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_utilisateur', 
        'numero_piece_identite', 
        'type_piece',
        'points_fidelite', 
        'preferences', 
        'notes_admin',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'id_client');
    }

    public function demandesVisite(): HasMany
    {
        return $this->hasMany(DemandeVisite::class, 'id_client');
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avi::class, 'id_client');
    }

    public function commandesServices(): HasMany
    {
        return $this->hasMany(CommandeService::class, 'id_client');
    }
}
