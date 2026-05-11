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
            $table->unsignedBigInteger('id_location_vehicule')->nullable()->after('id_demande_visite');
            $table->unsignedBigInteger('id_commande_service')->nullable()->after('id_location_vehicule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['id_location_vehicule', 'id_commande_service']);
        });
    }
};
