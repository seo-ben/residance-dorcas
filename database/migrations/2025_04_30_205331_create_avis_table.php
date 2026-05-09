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
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_client')->constrained('clients');
            $table->foreignId('id_reservation')->constrained('reservations');
            $table->foreignId('id_chambre')->constrained('appartement');
            $table->integer('note_globale')->unsigned()->between(1, 5);
            $table->integer('note_proprete')->unsigned()->nullable()->between(1, 5);
            $table->integer('note_service')->unsigned()->nullable()->between(1, 5);
            $table->integer('note_emplacement')->unsigned()->nullable()->between(1, 5);
            $table->text('commentaire')->nullable();
            $table->timestamp('date_avis')->useCurrent();
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->text('reponse_admin')->nullable();
            $table->timestamp('date_reponse')->nullable();
            $table->foreignId('id_admin_reponse')->nullable()->constrained('administrateurs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
