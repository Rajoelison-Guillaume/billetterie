<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    public function run()
    {
        // On récupère toutes les salles existantes
        $rooms = Room::all();

        // Pour chaque salle, on génère des sièges
        foreach ($rooms as $room) {
            // Exemple : 10 rangées de A à J
            $rows = range('A', 'J');
            $seatsPerRow = 10; // tu peux ajuster selon la capacité

            foreach ($rows as $row) {
                for ($i = 1; $i <= $seatsPerRow; $i++) {
                    Seat::create([
                        'room_id' => $room->id,
                        'row_label' => $row,
                        'seat_number' => $i,
                    ]);
                }
            }
        }
    }
}
