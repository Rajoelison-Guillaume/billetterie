<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatReservation extends Model
{
    protected $fillable = ['showtime_id','seat_id','ticket_id','user_id','reserved_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function showtime() {
        return $this->belongsTo(Showtime::class);
    }

    public function seat() {
        return $this->belongsTo(Seat::class);
    }

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}

