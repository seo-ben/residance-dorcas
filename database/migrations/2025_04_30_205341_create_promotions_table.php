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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 100);
            $table->text('description')->nullable();
            $table->string('code_promo', 50)->unique()->nullable();
            $table->enum('type_reduction', ['pourcentage', 'montant_fixe', 'nuit_gratuite']);
            $table->decimal('valeur_reduction', 10, 2);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->text('conditions')->nullable();
            $table->integer('limite_utilisation')->nullable();
            $table->integer('nb_utilisations')->default(0);
            $table->enum('statut', ['actif', 'inactif', 'expire'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
