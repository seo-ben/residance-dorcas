<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'email', 'password', 'name', 'prenom', 'telephone', 'adresse', 'ville',
        'pays', 'code_postal', 'date_naissance', 'type_utilisateur', 'statut',
        'date_creation', 'derniere_connexion', 'google_id', 'avatar'
    ];
    public function administrateur(): HasOne
    {
        return $this->hasOne(Administrateur::class, 'id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id_utilisateur');
    }

    public function messagesEnvoyes(): HasMany
    {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    public function messagesRecus(): HasMany
    {
        return $this->hasMany(Message::class, 'destinataire_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'id_utilisateur');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(LogSysteme::class, 'id_utilisateur');
    }

    public function tokensReinitialisation(): HasMany
    {
        return $this->hasMany(TokenReinitialisation::class, 'id_utilisateur');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function favoris()
    {
        return $this->belongsToMany(Chambre::class, 'favoris', 'user_id', 'chambre_id')->withTimestamps();
    }
}
