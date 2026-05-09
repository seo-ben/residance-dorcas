<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationNotificationMail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Envoie une notification à un utilisateur et l'enregistre dans la table notifications
     */
    public function sendNotification(User $user, ?Reservation $reservation, string $type, string $titre, string $message)
    {
        try {
            // Créer l'entrée dans la table notifications
            $notification = Notification::create([
                'id_utilisateur' => $user->id,
                'titre' => $titre,
                'message' => $message,
                'type_notification' => $type,
                'date_creation' => now(),
                'lue' => false,
                'lien' => $reservation ? route('reservations.show', $reservation->id) : null,
            ]);

            // Envoyer la notification selon le type
            if ($type === 'email') {
                Mail::to($user->email)->send(new ReservationNotificationMail($reservation, $message, $titre));
            } elseif ($type === 'sms') {
                // Implémenter la logique SMS (ex. Twilio)
                // Exemple : $this->sendSms($user->phone, $message);
                Log::info('SMS non implémenté pour l\'utilisateur : ' . $user->id);
            }

            return $notification;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Méthode pour tester l'envoi d'une notification (utilisée dans la route /test-email)
     */
    public function sendTestNotification(Reservation $reservation, string $email)
    {
        try {
            $user = User::where('email', $email)->firstOrFail();
            $message = "Test de notification pour la réservation {$reservation->reference}.";
            return $this->sendNotification(
                $user,
                $reservation,
                'email',
                'Test de notification',
                $message
            );
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification de test', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}