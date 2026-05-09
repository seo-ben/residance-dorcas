<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperLogSysteme
 */
class LogSysteme extends Model
{
    use HasFactory;
    protected $table = 'logs_systeme';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_utilisateur', 'action', 'description', 'adresse_ip',
        'user_agent', 'date_action', 'niveau'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}
