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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Administrateur ayant effectué l\'action');
            $table->string('action')->comment('Type d\'action effectuée (ex. update_status, refund)');
            $table->string('model_type')->comment('Type de modèle affecté (ex. App\Models\Reservation)');
            $table->unsignedBigInteger('model_id')->comment('ID du modèle affecté');
            $table->json('details')->nullable()->comment('Détails supplémentaires de l\'action');
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
