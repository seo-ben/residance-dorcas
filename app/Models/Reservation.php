<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

/**
 * @mixin IdeHelperReservation
 */
class Reservation extends Model
{
    use HasFactory;
    
    protected $table = 'reservations';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_client', 'reference', 'date_creation', 'date_arrivee', 'date_depart', 'statut',
        'type_reservation', 'prix_total', 'prix_original', 'reduction_montant', 'reduction_pourcentage', 
        'acompte_paye', 'code_promo', 'reduction_appliquee', 'notes_client', 'notes_admin', 'id_demande_visite'
    ];

    protected $dates = [
        'date_creation',
        'date_arrivee',
        'date_depart',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_arrivee' => 'date',
        'date_depart' => 'date',
        'prix_total' => 'decimal:2',
        'acompte_paye' => 'decimal:2',
        'reduction_appliquee' => 'decimal:2'
    ];

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function demandeVisite(): BelongsTo
    {
        return $this->belongsTo(DemandeVisite::class, 'id_demande_visite');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailReservation::class, 'id_reservation');
    }

    public function contrat(): HasMany
    {
        return $this->hasMany(Contrat::class, 'id_reservation');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'id_reservation');
    }

    public function commandesServices(): HasMany
    {
        return $this->hasMany(CommandeService::class, 'id_reservation');
    }

    public function avis(): HasMany
    {
        return $this->hasMany(Avi::class, 'id_reservation');
    }

    public function locationVehicule(): HasOne
    {
        return $this->hasOne(LocationVehicule::class, 'id_reservation');
    }

    // Accesseurs pour les dates formatées
    public function getDateArriveeFormatAttribute()
    {
        return $this->date_arrivee ? $this->date_arrivee->format('Y-m-d') : null;
    }

    public function getDateDepartFormatAttribute()
    {
        return $this->date_depart ? $this->date_depart->format('Y-m-d') : null;
    }

    public function getDateArriveeDisplayAttribute()
    {
        return $this->date_arrivee ? $this->date_arrivee->format('d/m/Y') : null;
    }

    public function getDateDepartDisplayAttribute()
    {
        return $this->date_depart ? $this->date_depart->format('d/m/Y') : null;
    }

    // Méthodes utilitaires
    public function getNombreJours()
    {
        if ($this->date_arrivee && $this->date_depart) {
            return $this->date_arrivee->diffInDays($this->date_depart);
        }
        return 0;
    }

    public function isPeutEtreModifiee()
    {
        return in_array($this->statut, ['brouillon', 'en_attente_paiement', 'acompte_paye']);
    }

    public function isEnCours()
    {
        return in_array($this->statut, ['confirmee', 'en_cours']);
    }

    public function isTerminee()
    {
        return in_array($this->statut, ['terminee', 'annulee']);
    }

    public function isPayee()
    {
        return $this->statut === 'terminee' || $this->paiements()->where('statut', 'valide')->exists();
    }

    public function isConfirmee()
    {
        return in_array($this->statut, ['confirmee', 'terminee']);
    }

    // Scopes
    public function scopeBrouillon($query)
    {
        return $query->where('statut', 'brouillon');
    }

    public function scopeEnAttentePaiement($query)
    {
        return $query->where('statut', 'en_attente_paiement');
    }

    public function scopeConfirmee($query)
    {
        return $query->where('statut', 'confirmee');
    }

    public function scopePourClient($query, $clientId)
    {
        return $query->where('id_client', $clientId);
    }
}