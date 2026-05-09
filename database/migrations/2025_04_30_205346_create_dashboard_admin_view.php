<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS dashboard_admin");

        $revenusQuery = DB::getDriverName() === 'sqlite'
            ? "date(date_paiement) >= date('now', '-30 days')"
            : "date_paiement >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)";

        DB::statement("
            CREATE VIEW dashboard_admin AS
            SELECT 
                (SELECT COUNT(*) FROM appartement WHERE statut = 'occupee') AS appartement_occupees,
                (SELECT COUNT(*) FROM appartement WHERE statut = 'disponible') AS appartement_disponibles,
                (SELECT COUNT(*) FROM reservations WHERE date_arrivee = CURRENT_DATE AND statut = 'confirmee') AS arrivees_jour,
                (SELECT COUNT(*) FROM reservations WHERE date_depart = CURRENT_DATE AND statut = 'en_cours') AS departs_jour,
                (SELECT COUNT(*) FROM demandes_visite WHERE statut = 'en_attente') AS demandes_visite_attente,
                (SELECT COUNT(*) FROM paiements WHERE statut = 'en_attente') AS paiements_attente,
                (SELECT SUM(montant) FROM paiements WHERE $revenusQuery AND statut = 'valide') AS revenus_30_jours;
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS dashboard_admin');
    }
};
