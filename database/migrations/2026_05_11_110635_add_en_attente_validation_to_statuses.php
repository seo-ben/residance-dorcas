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
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('statut')->change();
            });
            Schema::table('locations_vehicules', function (Blueprint $table) {
                $table->string('statut')->change();
            });
            Schema::table('commandes_services', function (Blueprint $table) {
                $table->string('statut')->change();
            });
            Schema::enableForeignKeyConstraints();
        } else {
             // MySQL / MariaDB
             \Illuminate\Support\Facades\DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_cours', 'brouillon', 'en_attente_paiement', 'acompte_paye', 'terminee', 'annulee', 'en_attente_validation') DEFAULT 'en_attente'");
             \Illuminate\Support\Facades\DB::statement("ALTER TABLE locations_vehicules MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_cours', 'terminee', 'annulee', 'en_attente_validation') DEFAULT 'en_attente'");
             \Illuminate\Support\Facades\DB::statement("ALTER TABLE commandes_services MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_cours', 'terminee', 'annulee', 'en_attente_validation') DEFAULT 'en_attente'");
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // On ne revient pas en arrière sur le type string ou l'ajout de valeur car c'est plus flexible
    }

};
