<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationSeat extends Model
{
    protected $table = 'reservation_seat';

    protected $fillable = [
        'reservation_id',
        'seat_id',
        'showtime_id',
        'ticket_id',
        'reserved_at',
    ];

    protected $dates = ['reserved_at'];

    // 🔗 Relation vers la réservation principale
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // 🔗 Relation vers le siège
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    // 🔗 Relation vers la séance
    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    // 🔗 Relation vers le ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
