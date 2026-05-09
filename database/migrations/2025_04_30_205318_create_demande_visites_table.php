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
        Schema::create('demandes_visite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_client')->constrained('clients');
            $table->foreignId('id_chambre')->constrained('appartement');
            $table->timestamp('date_demande')->useCurrent();
            $table->dateTime('date_visite_souhaitee');
            $table->text('message')->nullable();
            $table->enum('statut', ['en_attente', 'confirmee', 'terminee', 'annulee'])->default('en_attente');
            $table->timestamp('date_confirmation')->nullable();
            $table->foreignId('id_admin_confirmation')->nullable()->constrained('users');
            $table->text('notes_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_visites');
    }
};
