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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_utilisateur')->constrained('users')->onDelete('cascade');
            $table->string('numero_piece_identite', 50)->nullable();
            $table->enum('type_piece', ['passeport', 'carte_identite', 'permis_conduire'])->nullable();
            $table->integer('points_fidelite')->default(0);
            $table->text('preferences')->nullable();
            $table->text('notes_admin')->nullable();
            $table->timestamps();
        });    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
