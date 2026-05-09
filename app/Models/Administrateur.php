<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperAdministrateur
 */
class Administrateur extends Model
{
    use HasFactory;

    protected $table = 'administrateurs';
    protected $primaryKey = 'id';
    protected $fillable = ['id_utilisateur', 'fonction', 'niveau_acces'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function demandesVisite(): HasMany
    {
        return $this->hasMany(DemandeVisite::class, 'id_admin_confirmation');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'id_admin_validation');
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avi::class, 'id_admin_reponse');
    }

    public function parametresSysteme(): HasMany
    {
        return $this->hasMany(ParametreSysteme::class, 'id_admin_modification');
    }

    public function periodesIndisponibilite(): HasMany
    {
        return $this->hasMany(PeriodeIndisponibilite::class, 'id_admin_creation');
    }
}