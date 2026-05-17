<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'room_id', 'quantity', 'status', 'reserved_at', 'seats'
    ];

    protected $dates = ['reserved_at'];

    public function user()      { return $this->belongsTo(User::class); }
    public function event()     { return $this->belongsTo(Event::class); }
    public function room()      { return $this->belongsTo(Room::class); }
    public function payment()   { return $this->hasOne(Payment::class); }

    public function reservationSeats()
    {
        return $this->hasMany(ReservationSeat::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'reservation_seat')
                    ->withPivot(['ticket_id', 'reserved_at'])
                    ->withTimestamps();
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, ReservationSeat::class,
            'reservation_id', 'id', 'id', 'ticket_id');
    }
    public function order()
    {
    return $this->belongsTo(Order::class); 
    }
}