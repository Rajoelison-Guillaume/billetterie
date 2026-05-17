<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationSeat extends Model
{
    protected $table = 'reservation_seat';

    protected $fillable = [
        'reservation_id', 'seat_id', 'ticket_id', 'reserved_at'
    ];

    protected $dates = ['reserved_at'];

    public function reservation() { return $this->belongsTo(Reservation::class); }
    public function seat()        { return $this->belongsTo(Seat::class); }
    public function ticket()      { return $this->belongsTo(Ticket::class); }
}