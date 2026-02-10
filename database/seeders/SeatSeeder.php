<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    public function run()
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            $totalSeats   = $room->capacity ?? 800; // ✅ dynamique selon la salle
            $seatsPerRow  = 20;
            $rows         = ceil($totalSeats / $seatsPerRow);

            // Supprimer les anciens sièges
            Seat::where('room_id', $room->id)->delete();

            for ($r = 0; $r < $rows; $r++) {
                $rowLabel = chr(65 + $r); // A, B, C, ...

                for ($s = 1; $s <= $seatsPerRow; $s++) {
                    $seatNumber = $s;

                    $seatIndex = $r * $seatsPerRow + $s;
                    if ($seatIndex > $totalSeats) break;

                    Seat::create([
                        'room_id'       => $room->id,
                        'row_label'     => $rowLabel,
                        'seat_number'   => $seatNumber,
                        'is_accessible' => false,
                    ]);
                }
            }
        }
    }
}
