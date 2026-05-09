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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_utilisateur')->constrained('users');
            $table->string('titre', 100);
            $table->text('message');
            $table->enum('type_notification', ['info', 'alerte', 'succes', 'erreur','sms','email'])->default('info');
            $table->timestamp('date_creation')->useCurrent();
            $table->boolean('lue')->default(false);
            $table->timestamp('date_lecture')->nullable();
            $table->string('lien', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
