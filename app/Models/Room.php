<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['venue_id','name','capacity','description'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
    public function generateSeats()
{
    $seatsPerRow = 20;
    $total       = $this->capacity;
    $rows        = ceil($total / $seatsPerRow);

    $this->seats()->delete();

    for ($r = 0; $r < $rows; $r++) {
        $rowLabel = chr(65 + $r);

        for ($s = 1; $s <= $seatsPerRow; $s++) {
            $seatIndex = $r * $seatsPerRow + $s;
            if ($seatIndex > $total) break;

            $this->seats()->create([
                'row_label'     => $rowLabel,
                'seat_number'   => $s,
                'is_accessible' => false,
            ]);
        }
    }
}

}
