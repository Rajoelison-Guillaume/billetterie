<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Ajouter les colonnes manquantes dans reservation_seat
        Schema::table('reservation_seat', function (Blueprint $table) {
            $table->foreignId('showtime_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('ticket_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('reserved_at')->useCurrent();
        });

        // 2. Migrer les données depuis seat_reservations
        $seatReservations = DB::table('seat_reservations')->get();

        foreach ($seatReservations as $sr) {
            // retrouver la réservation liée via ticket_id si possible
            $reservationId = null;
            if ($sr->ticket_id) {
                $reservationId = DB::table('tickets')
                    ->where('id', $sr->ticket_id)
                    ->value('reservation_id');
            }

            // insérer dans reservation_seat
            if ($reservationId) {
                DB::table('reservation_seat')->insert([
                    'reservation_id' => $reservationId,
                    'seat_id'        => $sr->seat_id,
                    'showtime_id'    => $sr->showtime_id,
                    'ticket_id'      => $sr->ticket_id,
                    'reserved_at'    => $sr->reserved_at,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        // 3. Supprimer l’ancienne table seat_reservations
        Schema::dropIfExists('seat_reservations');
    }

    public function down(): void
    {
        // recréer seat_reservations si rollback
        Schema::create('seat_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showtime_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('reserved_at')->useCurrent();
            $table->timestamps();
        });

        // supprimer les colonnes ajoutées dans reservation_seat
        Schema::table('reservation_seat', function (Blueprint $table) {
            $table->dropColumn(['showtime_id', 'ticket_id', 'reserved_at']);
        });
    }
};
