<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateEventsTable extends Migration
{
    public function up()
    {
        // Étape 1 : ajouter la colonne slug sans unique
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
        });

        // Étape 2 : remplir slug pour les événements existants
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            $slug = Str::slug($event->title ?? 'event-' . $event->id);
            DB::table('events')->where('id', $event->id)->update(['slug' => $slug]);
        }

        // Étape 3 : rendre slug unique et non nul
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });

        // Étape 4 : supprimer max_per_user si présent
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'max_per_user')) {
                $table->dropColumn('max_per_user');
            }
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'slug')) {
                $table->dropColumn('slug');
            }
            if (!Schema::hasColumn('events', 'max_per_user')) {
                $table->integer('max_per_user')->default(10);
            }
        });
    }
}
