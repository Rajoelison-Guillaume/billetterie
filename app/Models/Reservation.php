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

    // 🔗 Relation avec les billets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // 🔗 Relation avec le paiement
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
