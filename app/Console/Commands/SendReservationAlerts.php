<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationAlert;

class SendReservationAlerts extends Command
{
    protected $signature = 'reservations:send-alerts';
    protected $description = 'Envoyer des alertes pour les réservations proches de leur fin';

    public function handle()
    {
        $reservations = Reservation::where('statut', 'terminee')
            ->where('date_arrivee', '<=', Carbon::today())
            ->where('date_depart', '>=', Carbon::today())
            ->get();
    
        foreach ($reservations as $reservation) {
            $joursRestants = Carbon::parse($reservation->date_depart)->diffInDays(Carbon::today(), false);
    
            if (in_array($joursRestants, [3, 1])) {
                // Envoyer une notification par e-mail
                Mail::to($reservation->client->user->email)->queue(new ReservationAlert($reservation, $joursRestants));
    
                // Log pour suivi
                \Log::info("Alerte envoyée pour la réservation {$reservation->reference}, {$joursRestants} jours restants.");
            }
        }
    
        $this->info('Alertes de réservation envoyées avec succès.');
    }
}