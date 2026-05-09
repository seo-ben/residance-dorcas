<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin IdeHelperChambre
 */
class Chambre extends Model
{
    use HasFactory;

    protected $table = 'appartement';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_propriete',
        'id_type_chambre',
        'numero_chambre',
        'etage',
        'prix_base',
        'statut',
        'notes',
        'date_derniere_maintenance'
    ];

    public function propriete(): BelongsTo
    {
        return $this->belongsTo(Propriete::class, 'id_propriete');
    }

    public function typeChambre(): BelongsTo
    {
        return $this->belongsTo(TypeChambre::class, 'id_type_chambre');
    }

    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'chambre_equipements', 'id_chambre', 'id_equipement')
            ->withPivot('quantite', 'notes');
    }

    public function medias(): HasMany
    {
        return $this->hasMany(Media::class, 'id_reference')->where('type_reference', 'chambre');
    }

    public function tarifs(): HasMany
    {
        return $this->hasMany(Tarif::class, 'id_chambre');
    }

    public function demandesVisite(): HasMany
    {
        return $this->hasMany(DemandeVisite::class, 'id_chambre');
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avi::class, 'id_chambre');
    }

    public function detailsReservations(): HasMany
    {
        return $this->hasMany(DetailReservation::class, 'id_chambre');
    }

    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Reservation::class,
            DetailReservation::class,
            'id_chambre', // Foreign key on DetailReservation
            'id', // Foreign key on Reservation
            'id', // Local key on Chambre
            'id_reservation' // Local key on DetailReservation
        );
    }

    public function periodesIndisponibilite(): HasMany
    {
        return $this->hasMany(PeriodeIndisponibilite::class, 'id_chambre');
    }

    public function usersFavoris()
    {
        return $this->belongsToMany(User::class, 'favoris', 'chambre_id', 'user_id')->withTimestamps();
    }
}
