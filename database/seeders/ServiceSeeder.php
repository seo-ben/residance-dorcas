<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'nom' => 'Petit Déjeuner Continental',
                'description' => 'Un assortiment de viennoiseries, fruits frais, jus naturels et boissons chaudes servi en chambre ou au restaurant.',
                'prix' => 5000,
                'duree_estimee' => 30, // minutes
                'disponibilite' => 'horaires_specifiques',
                'horaires_debut' => '07:00:00',
                'horaires_fin' => '10:30:00',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Blanchisserie & Repassage',
                'description' => 'Service de nettoyage à sec et repassage professionnel pour vos vêtements délicats. Récupération et livraison en chambre.',
                'prix' => 2500,
                'duree_estimee' => 1440, // minutes (24h)
                'disponibilite' => 'jour',
                'horaires_debut' => '08:00:00',
                'horaires_fin' => '18:00:00',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Navette Aéroport',
                'description' => 'Transport privatisé depuis ou vers l\'aéroport Gnassingbé Eyadéma. Chauffeur professionnel et véhicule climatisé.',
                'prix' => 10000,
                'duree_estimee' => 45, // minutes
                'disponibilite' => '24h',
                'horaires_debut' => '00:00:00',
                'horaires_fin' => '23:59:59',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Location de Voiture avec Chauffeur',
                'description' => 'Mise à disposition d\'un véhicule de standing avec chauffeur pour vos déplacements professionnels ou touristiques à Lomé.',
                'prix' => 35000,
                'duree_estimee' => 480, // minutes (8h)
                'disponibilite' => 'jour',
                'horaires_debut' => '08:00:00',
                'horaires_fin' => '20:00:00',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Dîner Gastronomique',
                'description' => 'Une expérience culinaire raffinée mêlant saveurs locales et cuisine internationale, préparée par notre chef résident.',
                'prix' => 15000,
                'duree_estimee' => 90, // minutes
                'disponibilite' => 'horaires_specifiques',
                'horaires_debut' => '19:00:00',
                'horaires_fin' => '22:30:00',
                'statut' => 'actif'
            ],
            [
                'nom' => 'Massages & Bien-être',
                'description' => 'Détendez-vous avec nos soins relaxants prodigués par des experts. Massage suédois, aux pierres chaudes ou aromathérapie.',
                'prix' => 20000,
                'duree_estimee' => 60, // minutes
                'disponibilite' => 'jour',
                'horaires_debut' => '09:00:00',
                'horaires_fin' => '20:00:00',
                'statut' => 'actif'
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
