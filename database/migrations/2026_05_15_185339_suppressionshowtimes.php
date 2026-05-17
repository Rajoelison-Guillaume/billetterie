<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Désactiver temporairement les contraintes de clés étrangères
        Schema::disableForeignKeyConstraints();

        // 1. Supprimer les colonnes showtime_id dans les tables qui en possèdent
        $tables = ['tickets', 'reservation_seat', 'session_seats'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'showtime_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['showtime_id']);
                    $table->dropColumn('showtime_id');
                });
            }
        }

        // 2. Supprimer la table session_seats (elle dépendait des showtimes)
        if (Schema::hasTable('session_seats')) {
            Schema::dropIfExists('session_seats');
        }

        // 3. Supprimer la table showtimes
        if (Schema::hasTable('showtimes')) {
            Schema::dropIfExists('showtimes');
        }

        // 4. Rendre room_id nullable dans events (un événement libre peut ne pas avoir de salle)
        if (Schema::hasTable('events') && Schema::hasColumn('events', 'room_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->unsignedBigInteger('room_id')->nullable()->change();
            });
        }

        // Réactiver les contraintes
        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        // Restauration possible si nécessaire (non demandée, on simplifie)
        Schema::disableForeignKeyConstraints();
        // On ne recrée pas showtimes car trop complexe
        Schema::enableForeignKeyConstraints();
    }
};