<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatReservation extends Model
{
    protected $fillable = [
        'showtime_id',
        'seat_id',
        'ticket_id',
        'reserved_at',
    ];

    
    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
