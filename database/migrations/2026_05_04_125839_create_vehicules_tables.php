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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('marque');
            $table->string('modele');
            $table->string('immatriculation')->unique();
            $table->string('type'); // SUV, Berline, etc.
            $table->string('transmission'); // Automatique, Manuelle
            $table->string('carburant'); // Essence, Diesel, etc.
            $table->integer('nb_places')->default(5);
            $table->decimal('prix_journalier', 10, 2);
            $table->text('description')->nullable();
            $table->enum('statut', ['disponible', 'loue', 'maintenance', 'indisponible'])->default('disponible');
            $table->json('caracteristiques')->nullable(); // AC, GPS, etc.
            $table->timestamps();
        });

        Schema::create('vehicule_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_vehicule')->constrained('vehicules')->onDelete('cascade');
            $table->string('chemin_image');
            $table->boolean('est_principale')->default(false);
            $table->timestamps();
        });

        Schema::create('locations_vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_vehicule')->constrained('vehicules');
            $table->foreignId('id_client')->constrained('clients');
            $table->foreignId('id_reservation')->nullable()->constrained('reservations'); // Optionnel: lié à un séjour
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->decimal('prix_total', 10, 2);
            $table->enum('statut', ['en_attente', 'confirmee', 'en_cours', 'terminee', 'annulee'])->default('en_attente');
            $table->enum('statut_paiement', ['non_paye', 'paye', 'acompte'])->default('non_paye');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations_vehicules');
        Schema::dropIfExists('vehicule_images');
        Schema::dropIfExists('vehicules');
    }
};
