<?php

namespace App\Listeners;

use App\Events\NewClientRegistrationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ClientRegistrationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendClientRegistrationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewClientRegistrationEvent  $event
     * @return void
     */
    public function handle(NewClientRegistrationEvent $event)
    {
        try {
            // Envoi d'email à l'utilisateur pour finaliser son inscription
            Mail::to($event->client->email)->send(new ClientRegistrationMail($event->client));
            
            Log::info('Email d\'inscription envoyé au client: ' . $event->client->email);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email d\'inscription: ' . $e->getMessage());
            
            // En cas d'erreur, on peut réessayer plus tard
            if ($this->attempts() < 3) {
                $this->release(30); // Réessayer dans 30 secondes
            }
        }
    }
}