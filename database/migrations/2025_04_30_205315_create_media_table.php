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
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->integer('id_reference');
            $table->enum('type_reference', ['chambre', 'propriete', 'type_chambre']);
            $table->enum('type_media', ['photo', 'video', 'visite_360', 'document']);
            $table->string('titre', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('chemin_fichier', 255);
            $table->boolean('est_couverture')->default(false);
            $table->integer('ordre')->default(0);
            $table->timestamp('date_ajout')->useCurrent();
            $table->timestamps();
        });    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
