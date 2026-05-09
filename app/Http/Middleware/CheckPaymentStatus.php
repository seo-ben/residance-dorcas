<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPaymentStatus
{
    public function handle(Request $request, Closure $next)
    {
        $reservation = $request->route('reservation');
        
        if (!$reservation) {
            abort(404);
        }

        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier si la réservation appartient à l'utilisateur
        if ($reservation->id_client !== auth()->id()) {
            abort(403);
        }

        // Vérifier si le paiement n'est pas déjà validé
        if ($reservation->paiement && $reservation->paiement->statut === 'validé') {
            return redirect()->route('reservations.index')
                ->with('error', 'Cette réservation a déjà été payée.');
        }

        return $next($request);
    }
}