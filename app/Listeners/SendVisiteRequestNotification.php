<?php

namespace App\Listeners;

use App\Events\VisiteRequestedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVisiteRequestNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VisiteRequestedEvent $event): void
    {
        //
    }
}
