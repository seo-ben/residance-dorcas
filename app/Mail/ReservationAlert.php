<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class ReservationAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $joursRestants;

    public function __construct(Reservation $reservation, $joursRestants)
    {
        $this->reservation = $reservation;
        $this->joursRestants = $joursRestants;
    }

    public function build()
    {
        return $this->subject('Alerte : Fin de votre réservation imminente')
                    ->view('emails.reservation_alert')
                    ->with([
                        'reservation' => $this->reservation,
                        'joursRestants' => $this->joursRestants,
                    ]);
    }
}