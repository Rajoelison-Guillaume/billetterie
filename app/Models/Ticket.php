<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'order_id', 'event_id', 'seat_id', 'ticket_type_id',
        'price', 'qr_code', 'status'
    ];

    public function order()     { return $this->belongsTo(Order::class); }
    public function event()     { return $this->belongsTo(Event::class); }
    public function seat()      { return $this->belongsTo(Seat::class); }
    public function ticketType() { return $this->belongsTo(TicketType::class); }

    public function hasSeat(): bool
    {
        return !is_null($this->seat_id);
    }
}