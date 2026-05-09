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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('name', 50);
            $table->string('prenom', 50)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville', 50)->nullable();
            $table->string('pays', 50)->nullable();
            $table->string('code_postal', 20)->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('type_utilisateur', ['admin', 'client'])->default('client');
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();    
            $table->timestamp('date_creation')->useCurrent();
            $table->timestamp('derniere_connexion')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'bloque'])->default('actif')->nullable();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn(['remember_token', 'email_verified_at']);
        // });
        
    }
};
