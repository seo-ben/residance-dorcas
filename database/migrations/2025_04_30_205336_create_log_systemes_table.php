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
        Schema::create('logs_systeme', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_utilisateur')->nullable()->constrained('users');
            $table->string('action', 255);
            $table->text('description')->nullable();
            $table->string('adresse_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('date_action')->useCurrent();
            $table->enum('niveau', ['debug', 'info', 'warning', 'error', 'critical'])->default('info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_systemes');
    }
};
