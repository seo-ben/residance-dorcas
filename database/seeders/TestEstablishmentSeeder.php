<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Propriete;
use App\Models\TypeChambre;
use App\Models\Chambre;
use App\Models\Equipement;
use App\Models\ParametreSysteme;
use App\Models\User;
use App\Models\Client;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Hash;

class TestEstablishmentSeeder extends Seeder
{
    public function run()
    {
        // 1. Création de l'Établissement (La Résidence)
        $propriete = Propriete::create([
            'nom' => 'Résidence Dorcas',
            'adresse' => 'Quartier Administratif, Lomé',
            'ville' => 'Lomé',
            'pays' => 'Togo',
            'code_postal' => 'BP 1234',
            'telephone' => '+228 90 00 00 01',
            'email' => 'contact@residence-dorcas.tg',
            'description' => 'Une résidence de luxe au cœur de Lomé, offrant confort et sécurité.',
            'etoiles' => 4,
            'statut' => 'actif'
        ]);

        // 2. Paramètres Système
        $settings = [
            ['cle' => 'currency', 'valeur' => 'FCFA', 'type_parametre' => 'affichage'],
            ['cle' => 'tax_rate', 'valeur' => '0.18', 'type_parametre' => 'application'],
            ['cle' => 'check_in_time', 'valeur' => '14:00', 'type_parametre' => 'application'],
            ['cle' => 'check_out_time', 'valeur' => '12:00', 'type_parametre' => 'application'],
            ['cle' => 'stripe_enabled', 'valeur' => '1', 'type_parametre' => 'systeme'],
        ];

        foreach ($settings as $s) {
            ParametreSysteme::updateOrCreate(['cle' => $s['cle']], $s);
        }

        // 3. Équipements
        $equipData = [
            ['nom' => 'Wi-Fi Haut Débit', 'icone' => 'wifi'],
            ['nom' => 'Climatisation', 'icone' => 'snowflake'],
            ['nom' => 'Smart TV', 'icone' => 'tv'],
            ['nom' => 'Mini Bar', 'icone' => 'cocktail'],
            ['nom' => 'Coffre-fort', 'icone' => 'lock'],
            ['nom' => 'Parking Gratuit', 'icone' => 'car'],
        ];

        $equipements = collect($equipData)->map(fn($e) => Equipement::create($e));

        // 4. Types de appartement
        $types = [
            [
                'nom' => 'Studio Standard',
                'description' => 'Parfait pour les séjours de courte durée.',
                'capacite_standard' => 1,
                'capacite_max' => 2,
                'prix_defaut' => 25000
            ],
            [
                'nom' => 'Appartement Deluxe',
                'description' => 'Espace salon et cuisine équipée.',
                'capacite_standard' => 2,
                'capacite_max' => 3,
                'prix_defaut' => 45000
            ],
            [
                'nom' => 'Suite Présidentielle',
                'description' => 'Le summum du luxe avec vue panoramique.',
                'capacite_standard' => 2,
                'capacite_max' => 4,
                'prix_defaut' => 85000
            ],
        ];

        foreach ($types as $t) {
            $prixDefaut = $t['prix_defaut'];
            unset($t['prix_defaut']);
            $typeModel = TypeChambre::create($t);

            // 5. Création des appartement (Appartements) pour chaque type
            for ($i = 1; $i <= 3; $i++) {
                $isLong = ($i % 2 == 0); // 1 appartement sur 2 est mixte ou dédié longue durée
                $chambre = Chambre::create([
                    'id_propriete' => $propriete->id,
                    'id_type_chambre' => $typeModel->id,
                    'numero_chambre' => $typeModel->nom[0] . '-' . (100 + $i + ($typeModel->id * 10)),
                    'etage' => 1,
                    'prix_base' => $prixDefaut,
                    'type_location' => $isLong ? 'mixte' : 'courte_duree',
                    'loyer_mensuel' => $isLong ? $prixDefaut * 15 : null, // Approx monthly rate
                    'frais_visite' => $isLong ? 5000 : null, // 5000 F CFA fee per visit
                    'statut' => 'disponible',
                ]);

                // Attacher quelques équipements aléatoires
                $chambre->equipements()->attach(
                    $equipements->random(rand(2, 4))->pluck('id')
                );
            }
        }

        // 6. Utilisateurs de test
        // Admin
        $adminUser = User::create([
            'name' => 'Admin',
            'prenom' => 'Propriétaire',
            'email' => 'admin@dorcas.tg',
            'telephone' => '+228 90 00 00 01',
            'password' => Hash::make('password'),
            'type_utilisateur' => 'admin',
        ]);
        Administrateur::create([
            'id_utilisateur' => $adminUser->id,
            'fonction' => 'Directeur',
            'niveau_acces' => 'super_admin'
        ]);

        // Client
        $clientUser = User::create([
            'name' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'client@test.com',
            'telephone' => '+228 99 88 77 66',
            'password' => Hash::make('password'),
            'type_utilisateur' => 'client',
        ]);
        Client::create([
            'id_utilisateur' => $clientUser->id,
            'points_fidelite' => 50
        ]);

        $this->command->info('Système de test initialisé avec succès !');
    }
}
