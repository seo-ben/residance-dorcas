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
        Schema::create('chambre_equipements', function (Blueprint $table) {
            $table->foreignId('id_chambre')->constrained('appartement')->onDelete('cascade');
            $table->foreignId('id_equipement')->constrained('equipements')->onDelete('cascade');
            $table->integer('quantite')->default(1);
            $table->text('notes')->nullable();
            $table->primary(['id_chambre', 'id_equipement']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambre_equipements');
    }
};
