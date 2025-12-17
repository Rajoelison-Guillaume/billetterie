<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionSeat extends Model
{
    protected $fillable = [
        'showtime_id',
        'seat_id',
        'status'
    ];

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
