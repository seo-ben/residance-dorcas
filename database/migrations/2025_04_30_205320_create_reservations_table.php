<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_client')->constrained('clients');
            $table->string('reference',100)->default('RES-00000000');
            $table->timestamp('date_creation')->useCurrent();
            $table->dateTime('date_arrivee');
            $table->dateTime('date_depart');
            $table->enum('statut', ['en_attente', 'confirmee', 'en_cours', 'brouillon', 'en_attente_paiement', 'acompte_paye', 'terminee', 'annulee'])->default('en_attente');
            $table->enum('type_reservation', ['court_terme', 'long_terme']);
            $table->decimal('prix_total', 10, 2);
            $table->decimal('prix_original', 10, 2);
            $table->decimal('reduction_montant', 10, 2)->nullable();
            $table->decimal('reduction_pourcentage', 10, 2)->nullable();
            $table->decimal('acompte_paye', 10, 2)->default(0.00);
            $table->string('code_promo', 50)->nullable();
            $table->decimal('reduction_appliquee', 10, 2)->default(0.00);
            $table->text('notes_client')->nullable();
            $table->text('notes_admin')->nullable();
            $table->foreignId('id_demande_visite')->nullable()->constrained('demandes_visite');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
