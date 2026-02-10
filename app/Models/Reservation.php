<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'quantity',
        'status',
        'reserved_at',
        'seats'
    ];

    protected $dates = ['reserved_at'];

    // 🔗 Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Relation avec l'événement
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // 🔗 Relation avec le paiement
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // 🔗 Relation avec la séance
    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    // 🔗 Relation pivot vers ReservationSeat (détails complets)
    public function reservationSeats()
    {
        return $this->hasMany(ReservationSeat::class);
    }

    // 🔗 Relation directe vers les sièges via pivot
    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'reservation_seat')
                    ->withPivot(['showtime_id', 'ticket_id', 'reserved_at'])
                    ->withTimestamps();
    }

    // 🔗 Relation vers les tickets via la table pivot
    public function tickets()
    {
        return $this->hasManyThrough(
            Ticket::class,
            ReservationSeat::class,
            'reservation_id', // clé étrangère sur reservation_seat
            'id',             // clé primaire de tickets
            'id',             // clé primaire de reservations
            'ticket_id'       // clé étrangère sur reservation_seat
        );
    }
}
