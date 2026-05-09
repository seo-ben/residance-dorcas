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
        Schema::create('appartement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_propriete')->constrained('proprietes');
            $table->foreignId('id_type_chambre')->constrained('types_appartement');
            $table->string('numero_chambre', 20);
            $table->string('etage', 10)->nullable();
            $table->decimal('prix_base', 10, 2);
            $table->enum('statut', ['disponible', 'occupee', 'nettoyage', 'maintenance'])->default('disponible');
            $table->text('notes')->nullable();
            $table->date('date_derniere_maintenance')->nullable();
            $table->unique(['id_propriete', 'numero_chambre']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartement');
    }
};
