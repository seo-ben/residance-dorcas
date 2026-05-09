<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * 
 * Le noyau de la console Laravel qui définit le planificateur de tâches
 * et enregistre les commandes Artisan personnalisées de l'application.
 */
class Kernel extends ConsoleKernel
{
    /**
     * Définit le planning des tâches programmées de l'application.
     *
     * Cette méthode configure les tâches qui doivent être exécutées à des intervalles réguliers
     * en utilisant le planificateur de tâches de Laravel. Elle est appelée automatiquement
     * par Laravel lorsque le cron job exécute `php artisan schedule:run`.
     *
     * @param Schedule $schedule Instance du planificateur de tâches Laravel
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Planifie la commande `reservations:send-alerts` pour s'exécuter quotidiennement à 08:00
        $schedule->command('reservations:send-alerts')
                 ->dailyAt('08:00') // Exécute la commande tous les jours à 08:00
                 ->timezone('Africa/Lome') // Définit le fuseau horaire (ajustez selon votre région)
                 ->withoutOverlapping() // Évite les exécutions simultanées
                 ->appendOutputTo(storage_path('logs/schedule.log')); // Enregistre la sortie dans un fichier de log
    }

    /**
     * Enregistre les commandes Artisan personnalisées de l'application.
     *
     * Cette méthode charge toutes les commandes définies dans le dossier `app/Console/Commands`
     * et inclut les commandes définies dans `routes/console.php`. Cela permet à Laravel
     * de reconnaître et d'exécuter les commandes Artisan personnalisées.
     *
     * @return void
     */
    protected function commands(): void
    {
        // Charge automatiquement toutes les commandes dans le dossier `app/Console/Commands`
        $this->load(__DIR__.'/Commands');

        // Inclut les commandes définies dans le fichier `routes/console.php`
        require base_path('routes/console.php');
    }
}