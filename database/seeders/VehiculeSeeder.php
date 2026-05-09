<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicule;
use App\Models\VehiculeImage;

class VehiculeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicules = [
            [
                'marque' => 'Toyota',
                'modele' => 'Land Cruiser Prado',
                'immatriculation' => 'TG-4567-BJ',
                'type' => 'SUV',
                'transmission' => 'Automatique',
                'carburant' => 'Diesel',
                'nb_places' => 7,
                'prix_journalier' => 75000,
                'description' => 'Un SUV tout-terrain de luxe, parfait pour les longs trajets et le confort absolu.',
                'statut' => 'disponible',
                'caracteristiques' => ['Climatisation', 'GPS', 'Bluetooth', '4x4', 'Cuir', 'Caméra de recul'],
            ],
            [
                'marque' => 'Mercedes-Benz',
                'modele' => 'Classe C',
                'immatriculation' => 'TG-8899-AX',
                'type' => 'Berline',
                'transmission' => 'Automatique',
                'carburant' => 'Essence',
                'nb_places' => 5,
                'prix_journalier' => 60000,
                'description' => 'Élégance et performance pour vos déplacements professionnels en ville.',
                'statut' => 'disponible',
                'caracteristiques' => ['Climatisation', 'Bluetooth', 'Toit Ouvrant', 'Cuir'],
            ],
            [
                'marque' => 'Hyundai',
                'modele' => 'Tucson',
                'immatriculation' => 'TG-1122-CC',
                'type' => 'SUV',
                'transmission' => 'Automatique',
                'carburant' => 'Essence',
                'nb_places' => 5,
                'prix_journalier' => 45000,
                'description' => 'SUV urbain polyvalent, idéal pour la famille et le confort quotidien.',
                'statut' => 'disponible',
                'caracteristiques' => ['Climatisation', 'GPS', 'Bluetooth', 'Caméra de recul'],
            ],
        ];

        foreach ($vehicules as $vData) {
            Vehicule::create($vData);
        }
    }
}
