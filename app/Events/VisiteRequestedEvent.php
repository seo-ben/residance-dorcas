<?php

namespace App\Events;

use App\Models\DemandeVisite;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VisiteRequestedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $visite;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\DemandeVisite  $visite
     * @return void
     */
    public function __construct(DemandeVisite $visite)
    {
        $this->visite = $visite;
    }
}