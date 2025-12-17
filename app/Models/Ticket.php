<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'order_id',
        'event_id',
        'showtime_id',
        'seat_id',
        'ticket_type_id',
        'session_seat_id',
        'quantity',
        'unit_price',
        'price',
        'qr_code',
        'status', // ex: unpaid, paid, cancelled
    ];

    // Relations
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function sessionSeat(): BelongsTo
    {
        return $this->belongsTo(SessionSeat::class);
    }
}
