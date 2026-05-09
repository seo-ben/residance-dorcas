# Installation des model avec les mgrations

php artisan make:model User -m
php artisan make:model Administrateur -m
php artisan make:model Client -m
php artisan make:model Propriete -m
php artisan make:model TypeChambre -m
php artisan make:model Chambre -m
php artisan make:model Equipement -m
php artisan make:model ChambreEquipement -m
php artisan make:model Media -m
php artisan make:model Tarif -m
php artisan make:model DemandeVisite -m
php artisan make:model Reservation -m
php artisan make:model DetailReservation -m
php artisan make:model Contrat -m
php artisan make:model Paiement -m
php artisan make:model Service -m
php artisan make:model CommandeService -m
php artisan make:model DetailCommandeService -m
php artisan make:model Avi -m
php artisan make:model Message -m
php artisan make:model Notification -m
php artisan make:model LogSysteme -m
php artisan make:model ParametreSysteme -m
php artisan make:model Promotion -m
php artisan make:model TokenReinitialisation -m
php artisan make:model PeriodeIndisponibilite -m
php artisan make:migration create_appartement_disponibles_view
php artisan make:migration create_dashboard_admin_view

# les package installer son dans composer

- Laravel Jetstream : Si vous souhaitez une authentification
  plus complète avec 2FA, gestion d'équipe
  composer require laravel/jetstream
  php artisan jetstream:install livewire --teams
  php artisan migrate
- Spatie Laravel Permission : Pour gérer les rôles et permissions
  (admin, client, personnel)
  composer require spatie/laravel-permission
  php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
  php artisan migrate
- Laravel Socialite : Pour permettre la connexion via les réseaux
  sociaux , pour un site complet de hôtelière ,comment utiliser  
   laravel Laravel Jetstream ,et Spatie Laravel Permission et Laravel
  Socialite
  composer require laravel/socialite

php artisan vendor:publish --tag=jetstream-views

php artisan db:seed
