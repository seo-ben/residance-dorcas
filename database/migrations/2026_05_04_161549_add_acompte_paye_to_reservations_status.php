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
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_cours', 'brouillon', 'en_attente_paiement', 'acompte_paye', 'terminee', 'annulee') DEFAULT 'en_attente'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_cours', 'brouillon', 'en_attente_paiement', 'terminee', 'annulee') DEFAULT 'en_attente'");
        }
    }
};
