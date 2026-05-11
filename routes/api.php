<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Client\AuthController;
use App\Http\Controllers\Api\Client\ChambreController;
use App\Http\Controllers\Api\Client\ProprieteController;
use App\Http\Controllers\Api\Client\ServiceController;
use App\Http\Controllers\Api\Client\VisiteController;
use App\Http\Controllers\Api\Client\FavorisController;
use App\Http\Controllers\Api\Client\ReservationController;
use App\Http\Controllers\Api\Client\VehiculeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes Client
Route::prefix('client')->group(function () {
    // Auth
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);
    Route::post('/register', [AuthController::class, 'register']);

    // Public routes (or filtered)
    Route::get('/appartements', [ChambreController::class, 'index']);
    Route::get('/proprietes', [ProprieteController::class, 'index']);
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/vehicules', [VehiculeController::class, 'index']);

    // Global Search (Public)
    Route::get('/search/instant', [\App\Http\Controllers\Api\Client\GlobalSearchController::class, 'instant']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/services/mes-commandes', [ServiceController::class, 'indexCommandes']);
        Route::post('/services/commander', [ServiceController::class, 'store']);
        
        Route::get('/vehicules/mes-locations', [VehiculeController::class, 'indexLocations']);
        Route::post('/vehicules/louer', [VehiculeController::class, 'book']);
        
        Route::get('/visites/mes-visites', [VisiteController::class, 'index']);
        Route::post('/visites', [VisiteController::class, 'store']);
        
        // Favoris
        Route::get('/favoris', [FavorisController::class, 'index']);
        Route::post('/favoris/{chambre}/toggle', [FavorisController::class, 'toggle']);
        
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::get('/reservations/{id}', [ReservationController::class, 'show']);
        Route::post('/reservations/{id}/annuler', [ReservationController::class, 'cancel']);
        Route::get('/reservations/{id}/paiement-link', [ReservationController::class, 'getPaymentLink']);
        
        // Paiements
        Route::post('/paiements/declarer', [\App\Http\Controllers\Api\Client\PaiementController::class, 'declarePayment']);
    });

    // Public Show routes (placed at the end to avoid conflict with specific routes like /mes-commandes)
    Route::get('/appartements/{id}', [ChambreController::class, 'show']);
    Route::get('/proprietes/{id}', [ProprieteController::class, 'show']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);
    Route::get('/vehicules/{id}', [VehiculeController::class, 'show']);
    Route::get('/visites/{id}', [VisiteController::class, 'show']);
});
