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
            $table->string('statut_paiement')->default('non_paye')->after('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes_services', function (Blueprint $table) {
            $table->dropColumn('statut_paiement');
        });
    }
};
