<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTokenReinitialisation
 */
class TokenReinitialisation extends Model
{
    use HasFactory;
    protected $table = 'token_reinitialisation';
    protected $primaryKey = 'id';
    protected $fillable = ['id_utilisateur', 'token', 'date_creation', 'date_expiration', 'utilisé'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}
