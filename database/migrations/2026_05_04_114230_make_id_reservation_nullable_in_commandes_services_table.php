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
        Schema::table('commandes_services', function (Blueprint $table) {
            // $table->foreignId('id_reservation')->nullable()->change(); // Already nullable in base migration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes_services', function (Blueprint $table) {
            // $table->foreignId('id_reservation')->nullable(false)->change();
        });
    }
};
