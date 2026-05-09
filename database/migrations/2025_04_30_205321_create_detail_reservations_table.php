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
        Schema::create('details_reservation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reservation')->constrained('reservations');
            $table->foreignId('id_chambre')->constrained('appartement');
            $table->decimal('prix_unitaire', 10, 2);
            $table->integer('quantite')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_reservations');
    }
};
