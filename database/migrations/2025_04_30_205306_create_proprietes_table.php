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
        Schema::create('proprietes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->text('adresse');
            $table->string('ville', 50);
            $table->string('pays', 50);
            $table->string('code_postal', 20);
            $table->string('telephone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('description')->nullable();
            $table->integer('etoiles')->unsigned()->nullable()->between(1, 5);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('date_ajout')->useCurrent();
            $table->enum('statut', ['actif', 'inactif', 'en_maintenance'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proprietes');
    }
};
