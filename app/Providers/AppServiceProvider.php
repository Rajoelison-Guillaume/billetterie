<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistrement des services
     */
    public function register(): void
    {
        //
    }

    /**
     * Démarrage des services
     */
    public function boot(): void
    {
        // ✅ Pour éviter les erreurs avec les index longs en MySQL
        Schema::defaultStringLength(191);

        // ✅ Forcer le fuseau horaire Madagascar
        date_default_timezone_set('Indian/Antananarivo');

        // ✅ Optionnel : configurer locale pour Carbon
        setlocale(LC_TIME, 'fr_FR.UTF-8');
    }
}
