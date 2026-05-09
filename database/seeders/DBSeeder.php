<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Réinitialiser les rôles et permissions mis en cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les rôles
        $adminRole = Role::create(['name' => 'super_admin']);
        $managerRole = Role::create(['name' => 'responsable']);
        $standardAdminRole = Role::create(['name' => 'standard']);
        $clientRole = Role::create(['name' => 'client']);

        // Créer les permissions
        $permissions = [
            // Propriétés
            'proprietes.view',
            'proprietes.create',
            'proprietes.edit',
            'proprietes.delete',
            // appartement
            'appartement.view',
            'appartement.create',
            'appartement.edit',
            'appartement.delete',
            // Réservations
            'reservations.view',
            'reservations.create',
            'reservations.edit',
            'reservations.delete',
            // Clients
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            // Paiements
            'paiements.view',
            'paiements.create',
            'paiements.edit',
            'paiements.approve',
            // Rapports
            'rapports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assignation des permissions aux rôles
        $adminRole->givePermissionTo(Permission::all());

        // Pour exclude des permissions, il faut modifier légèrement la syntaxe
        $allPermissionsExceptDelete = Permission::whereNotIn('name', ['proprietes.delete'])->get();
        $managerRole->givePermissionTo($allPermissionsExceptDelete);

        $standardAdminRole->givePermissionTo([
            'proprietes.view',
            'appartement.view',
            'reservations.view',
            'clients.view'
        ]);

        $clientRole->givePermissionTo([
            'reservations.create',
            'paiements.create'
        ]);
    }
}
