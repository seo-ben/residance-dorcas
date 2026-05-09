<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientChambreController;
use App\Http\Controllers\ClientProprietesController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ClientVisiteController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\ClientServiceController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Google Authentication
Route::get('auth/google', [App\Http\Controllers\LoginWithGoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [App\Http\Controllers\LoginWithGoogleController::class, 'handleGoogleCallback']);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/a-propos', [HomeController::class, 'apropos'])->name('a-propos');
Route::get('/services', [ClientServiceController::class, 'index'])->name('services');
Route::post('/services/commander', [ClientServiceController::class, 'store'])->name('services.store');

// Public - Rooms & Availability (Consolidated)
Route::prefix('chambres')->name('chambres.')->group(function () {
    Route::get('/', [ClientChambreController::class, 'index'])->name('index');
    Route::get('/search', [HomeController::class, 'search'])->name('search');
    Route::get('/{id}', [HomeController::class, 'show'])->name('show');
    Route::post('/{id}/demander-visite', [HomeController::class, 'demanderVisite'])->name('demander-visite');
    Route::get('/{id}/check-availability', [HomeController::class, 'checkAvailability'])->name('check.availability');

    Route::prefix('proprietes')->name('proprietes.')->group(function () {
        Route::get('/', [ClientProprietesController::class, 'index'])->name('index');
        Route::get('/{id}', [ClientProprietesController::class, 'show'])->name('show');
    });
});

// Véhicules Public
Route::prefix('vehicules')->name('vehicules.')->group(function () {
    Route::get('/', [App\Http\Controllers\VehiculeController::class, 'index'])->name('index');
    Route::get('/{id}', [App\Http\Controllers\VehiculeController::class, 'show'])->name('show');
    Route::post('/book', [App\Http\Controllers\VehiculeController::class, 'book'])->name('book');
    Route::get('/{id}/confirmation', [App\Http\Controllers\VehiculeController::class, 'confirmation'])->name('confirmation');
});

Route::get('/proprietes/{propriete}/appartement', [ClientChambreController::class, 'byPropriete'])->name('proprietes.appartement');
Route::get('/reservations/qr-display', [ReservationController::class, 'qrDisplay'])->name('reservations.qr-display');
Route::post('/stripe/webhook', [ReservationController::class, 'handleWebhook'])->name('stripe.webhook');

// Leekpay Routes
Route::prefix('paiement/leekpay')->name('paiement.leekpay.')->group(function () {
    Route::get('/success', [App\Http\Controllers\LeekpayController::class, 'success'])->name('success');
    Route::get('/cancel', [App\Http\Controllers\LeekpayController::class, 'cancel'])->name('cancel');
    Route::post('/webhook', [App\Http\Controllers\LeekpayController::class, 'webhook'])->name('webhook');

    // Initiation
    Route::get('/reservations/{id}', [App\Http\Controllers\LeekpayController::class, 'initiateReservationPayment'])->name('initiate.reservation');
    Route::get('/visites/{id}', [App\Http\Controllers\LeekpayController::class, 'initiateVisitPayment'])->name('initiate.visit');
});

/*
|--------------------------------------------------------------------------
| Client Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // Favorites
    Route::post('/appartement/{chambre}/favoris', [FavorisController::class, 'toggle'])->name('favoris.toggle');
    Route::get('/favoris', [FavorisController::class, 'index'])->name('favoris.index');

    // Bookings
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/store', [ReservationController::class, 'store'])->name('store');
        Route::get('/{id}/continue', [ReservationController::class, 'create'])->name('continue');
        Route::get('/{id}', [ReservationController::class, 'show'])->name('show');
        Route::get('/{id}/confirmation', [ReservationController::class, 'confirmation'])->name('confirmation');
        Route::post('/{id}/cancel', [ReservationController::class, 'cancel'])->name('cancel');

        // Payments
        Route::get('/{id}/payment', [ReservationController::class, 'payment'])->name('payment');
        Route::get('/{reservation}/payment/success', [ReservationController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/{reservation}/payment/cancel', [ReservationController::class, 'paymentCancel'])->name('payment.cancel');

        // Historical / Reports
        Route::get('/paiements/liste', [ReservationController::class, 'paiementIndex'])->name('paiements.index');
    });

    // Client Visits
    Route::prefix('mes-visites')->name('client.visites.')->group(function () {
        Route::get('/', [ClientVisiteController::class, 'index'])->name('index');
        Route::get('/{id}/visite', [ClientVisiteController::class, 'show'])->name('show');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::name('admin.')->prefix('admin')->group(function () {
    require __DIR__ . '/admin.php';
});

// Test Email
Route::get('/test-email', function () {
    $reservation = \App\Models\Reservation::first();
    \Illuminate\Support\Facades\Mail::to('digitalforge17@gmail.com')->send(new \App\Mail\ReservationAlert($reservation, 3));
    return 'Email envoyé !';
});
