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
        Schema::table('appartement', function (Blueprint $table) {
            $table->enum('type_location', ['courte_duree', 'longue_duree', 'mixte'])->default('courte_duree')->after('prix_base');
            $table->decimal('loyer_mensuel', 10, 2)->nullable()->after('type_location');
            $table->decimal('frais_visite', 10, 2)->nullable()->after('loyer_mensuel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appartement', function (Blueprint $table) {
            $table->dropColumn(['type_location', 'loyer_mensuel', 'frais_visite']);
        });
    }
};
