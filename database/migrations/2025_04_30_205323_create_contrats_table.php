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
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reservation')->constrained('reservations');
            $table->string('reference', 50)->unique();
            $table->date('date_signature')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('montant_mensuel', 10, 2);
            $table->decimal('depot_garantie', 10, 2);
            $table->enum('statut', ['brouillon', 'envoye', 'signe', 'actif', 'termine', 'resilie'])->default('brouillon');
            $table->string('fichier_contrat', 255)->nullable();
            $table->text('conditions_speciales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
