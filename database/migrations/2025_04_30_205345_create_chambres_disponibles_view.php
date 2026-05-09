<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS appartement_disponibles");
        DB::statement("
            CREATE VIEW appartement_disponibles AS
            SELECT c.id, c.id_propriete, c.id_type_chambre, c.numero_chambre, 
                   c.etage, c.prix_base, p.nom AS nom_propriete, p.ville, p.pays,
                   tc.nom AS type_chambre, tc.capacite_standard, tc.capacite_max,
                   tc.superficie
            FROM appartement c
            JOIN proprietes p ON c.id_propriete = p.id
            JOIN types_appartement tc ON c.id_type_chambre = tc.id
            WHERE c.statut = 'disponible'
            AND p.statut = 'actif';
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS appartement_disponibles');
    }
};
