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
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_chambre')->constrained('appartement');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('prix', 10, 2);
            $table->enum('type_tarif', ['standard', 'weekend', 'saison_haute', 'saison_basse', 'promotion'])->default('standard');
            $table->decimal('pourcentage_reduction', 5, 2)->default(0.00);
            $table->integer('minimum_nuits')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
