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

        foreach ($rooms as $room) {
            $totalSeats = 800; // par défaut
            $seatsPerRow = 20; // nombre de sièges par rangée
            $rows = ceil($totalSeats / $seatsPerRow);

            // Générer les labels de rangées (A, B, C, ...)
            $rowLabels = [];
            for ($i = 0; $i < $rows; $i++) {
                $rowLabels[] = chr(65 + $i); // 65 = 'A'
            }

            foreach ($rowLabels as $row) {
                for ($i = 1; $i <= $seatsPerRow; $i++) {
                    if (Seat::where('room_id', $room->id)->count() >= $totalSeats) {
                        break 2;
                    }

                    Seat::create([
                        'room_id' => $room->id,
                        'row_label' => $row,
                        'seat_number' => $i,
                        'is_accessible' => false,
                    ]);
                }
            }
        }
    }
}
