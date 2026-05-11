<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\TypeChambreController;
use App\Http\Controllers\ProprieteController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\RoomManagementController;
use App\Http\Controllers\FinanceController;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    // API Stats
    Route::get('/api/reservations-stats', [AdminController::class, 'reservationsStats'])->name('api.reservations-stats');
    Route::get('/api/occupancy-stats', [AdminController::class, 'occupancyStats'])->name('api.occupancy-stats');
    Route::get('/api/chart-stats', [AdminController::class, 'chartStats'])->name('api.chart-stats');

    // appartement
    Route::prefix('appartement')->name('chambres.')->group(function () {
        Route::get('/', [ChambreController::class, 'index'])->name('index');
        Route::get('/create', [ChambreController::class, 'create'])->name('create');
        Route::post('/', [ChambreController::class, 'store'])->name('store');
        Route::get('/{chambre}/edit', [ChambreController::class, 'edit'])->name('edit');
        Route::put('/{chambre}', [ChambreController::class, 'update'])->name('update');
        Route::get('/{chambre}/detail', [ChambreController::class, 'show'])->name('show');
        Route::delete('/{chambre}', [ChambreController::class, 'destroy'])->name('destroy');
        Route::delete('/media/{id}', [ChambreController::class, 'deleteMedia'])->name('media.delete');
    });

    // Types appartement
    Route::prefix('types-chambres')->name('types-chambres.')->group(function () {
        Route::get('/', [TypeChambreController::class, 'index'])->name('index');
        Route::get('/create', [TypeChambreController::class, 'create'])->name('create');
        Route::post('/', [TypeChambreController::class, 'store'])->name('store');
        Route::get('/{typeChambre}/show', [TypeChambreController::class, 'show'])->name('show');
        Route::get('/{typeChambre}/edit', [TypeChambreController::class, 'edit'])->name('edit');
        Route::put('/{typeChambre}', [TypeChambreController::class, 'update'])->name('update');
        Route::delete('/{typeChambre}', [TypeChambreController::class, 'destroy'])->name('destroy');
    });

    // Propriétés
    Route::prefix('proprietes')->name('proprietes.')->group(function () {
        Route::get('/', [ProprieteController::class, 'index'])->name('index');
        Route::get('/create', [ProprieteController::class, 'create'])->name('create');
        Route::post('/', [ProprieteController::class, 'store'])->name('store');
        Route::get('/{propriete}', [ProprieteController::class, 'show'])->name('show');
        Route::get('/{propriete}/edit', [ProprieteController::class, 'edit'])->name('edit');
        Route::put('/{propriete}', [ProprieteController::class, 'update'])->name('update');
        Route::delete('/{propriete}', [ProprieteController::class, 'destroy'])->name('destroy');
    });

    // Réservations Admin
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [AdminReservationController::class, 'index'])->name('index');
        Route::get('/create', [AdminReservationController::class, 'create'])->name('create');
        Route::post('/', [AdminReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [AdminReservationController::class, 'show'])->name('show');
        Route::put('/{reservation}/update-status', [AdminReservationController::class, 'updateStatus'])->name('update-status');
        Route::post('/{reservation}/store-payment', [AdminReservationController::class, 'storePayment'])->name('store-payment');
        Route::get('/{reservation}/communications', [AdminReservationController::class, 'communicationHistory'])->name('communications');
        Route::post('/{reservation}/notify', [AdminReservationController::class, 'notifyClient'])->name('notifyClient');
        Route::get('/export/csv', [AdminReservationController::class, 'exportCsv'])->name('export');
    });

    // Demandes de visite
    Route::prefix('demandes-visite')->name('demandes-visite.')->group(function () {
        Route::get('/', [AdminReservationController::class, 'demandesVisite'])->name('index');
        Route::post('/{demande}/schedule', [AdminReservationController::class, 'scheduleVisite'])->name('schedule');
        Route::put('/{demande}/reject', [AdminReservationController::class, 'rejectVisite'])->name('reject');
        Route::put('/{demande}/confirm', [AdminReservationController::class, 'confirmVisite'])->name('confirm');
    });

    // Finance
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/rapports', [FinanceController::class, 'rapports'])->name('rapports');
        Route::get('/encaissement', [FinanceController::class, 'createEncaissement'])->name('encaissement.create');
        Route::post('/encaissement', [FinanceController::class, 'storeEncaissement'])->name('encaissement.store');
        
        Route::get('/paiements-en-attente', [FinanceController::class, 'pendingPayments'])->name('paiements.pending');
        Route::post('/paiements/{paiement}/approuver', [FinanceController::class, 'approvePayment'])->name('paiements.approve');
        Route::post('/paiements/{paiement}/rejeter', [FinanceController::class, 'rejectPayment'])->name('paiements.reject');
        Route::get('/transactions', [FinanceController::class, 'transactions'])->name('transactions');
        Route::post('/{paiement}/refund', [FinanceController::class, 'refund'])->name('refund');
        Route::get('/export', [FinanceController::class, 'export'])->name('export');
        Route::get('/audit', [FinanceController::class, 'audit'])->name('audit');
    });

    // Paiements
    Route::prefix('paiements')->name('paiements.')->group(function () {
        Route::get('/', [AdminReservationController::class, 'paiements'])->name('index');
        Route::post('/{paiement}/refund', [AdminReservationController::class, 'refund'])->name('refund');
    });

    // Rooms Management (Maintenance/Status)
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [RoomManagementController::class, 'index'])->name('index');
        Route::patch('/{id}/status', [RoomManagementController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/maintenance', [RoomManagementController::class, 'createMaintenance'])->name('createMaintenance');
    });

    // Médias
    Route::delete('/medias/{media}', [MediaController::class, 'destroy'])->name('medias.destroy');

    // Services
    Route::prefix('services')->name('services.')->group(function () {
        // Commandes de services
        Route::get('/commandes', [App\Http\Controllers\AdminServiceController::class, 'orders'])->name('orders');
        Route::put('/commandes/{order}/status', [App\Http\Controllers\AdminServiceController::class, 'updateOrderStatus'])->name('update-order-status');

        Route::get('/', [App\Http\Controllers\AdminServiceController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdminServiceController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\AdminServiceController::class, 'store'])->name('store');
        Route::get('/{service}/edit', [App\Http\Controllers\AdminServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [App\Http\Controllers\AdminServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [App\Http\Controllers\AdminServiceController::class, 'destroy'])->name('destroy');
    });

    // Véhicules
    Route::prefix('vehicules')->name('vehicules.')->group(function () {
        // Locations de véhicules
        Route::get('/locations', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'rentals'])->name('rentals');
        Route::put('/locations/{rental}/status', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'updateRentalStatus'])->name('update-rental-status');

        Route::get('/', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'store'])->name('store');
        Route::get('/{vehicule}', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'show'])->name('show');
        Route::get('/{vehicule}/edit', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'edit'])->name('edit');
        Route::put('/{vehicule}', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'update'])->name('update');
        Route::delete('/{vehicule}', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'destroy'])->name('destroy');
        Route::post('/{vehicule}/images/{image}/set-primary', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'setPrimaryImage'])->name('images.primary');
        Route::delete('/images/{image}', [App\Http\Controllers\Admin\AdminVehiculeController::class, 'deleteImage'])->name('images.delete');
    });

    // Equipements
    Route::prefix('equipements')->name('equipements.')->group(function () {
        Route::get('/', [EquipementController::class, 'index'])->name('index');
        Route::get('/create', [EquipementController::class, 'create'])->name('create');
        Route::post('/', [EquipementController::class, 'store'])->name('store');
        Route::get('/{equipement}/edit', [EquipementController::class, 'edit'])->name('edit');
        Route::put('/{equipement}', [EquipementController::class, 'update'])->name('update');
        Route::delete('/{equipement}', [EquipementController::class, 'destroy'])->name('destroy');
    });
});
