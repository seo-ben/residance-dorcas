<?php

namespace App\Services;

use App\Models\User;
use App\Models\Reservation;

class LoyaltyService
{
    /**
     * Calcule le niveau de fidélité d'un utilisateur (Type Genius)
     */
    public function getLoyaltyLevel(User $user)
    {
        // On récupère le client associé si nécessaire, ou on utilise directement l'ID de l'user
        // si les réservations sont liées à l'utilisateur. 
        // Note: Dans votre schéma, Reservation a id_client.
        
        $client = $user->client;
        if (!$client) return 0;

        $completedBookings = Reservation::where('id_client', $client->id)
            ->where('statut', 'terminee')
            ->count();

        if ($completedBookings >= 10) return 3; // Genius Niveau 3
        if ($completedBookings >= 5)  return 2; // Genius Niveau 2
        if ($completedBookings >= 2)  return 1; // Genius Niveau 1
        return 0;
    }

    /**
     * Applique une réduction basée sur le niveau
     */
    public function applyLoyaltyDiscount($price, $level)
    {
        $discounts = [
            0 => 0,
            1 => 0.10, // 10%
            2 => 0.15, // 15%
            3 => 0.20, // 20%
        ];

        $discountRate = $discounts[$level] ?? 0;
        return $price * (1 - $discountRate);
    }
}
