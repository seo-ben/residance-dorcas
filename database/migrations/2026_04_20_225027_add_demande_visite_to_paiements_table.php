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
        Schema::table('paiements', function (Blueprint $table) {
            // $table->foreignId('id_reservation')->nullable()->change(); // Already nullable in base migration
            $table->foreignId('id_demande_visite')->nullable()->after('id_reservation')->constrained('demandes_visite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropForeign(['id_demande_visite']);
            $table->dropColumn('id_demande_visite');
            // $table->foreignId('id_reservation')->nullable(false)->change();
        });
    }
};
