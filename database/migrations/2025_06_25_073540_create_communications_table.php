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
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade')->comment('Réservation associée');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade')->comment('Administrateur ayant envoyé la communication');
            $table->string('type')->comment('Type de communication (email, sms)');
            $table->text('message')->comment('Contenu du message');
            $table->timestamp('sent_at')->nullable()->comment('Date d\'envoi');
            $table->timestamps();

            $table->index('reservation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};
