<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Showtime extends Model
{
    protected $fillable = ['event_id','room_id','start_at','end_at','price'];

    public function event():BelongsTo{ return $this->belongsTo(Event::class); }
    public function room():BelongsTo{ return $this->belongsTo(Room::class); }
    public function seatReservations():HasMany { return $this->hasMany(SeatReservation::class); }
    public function tickets():HasMany { return $this->hasMany(Ticket::class); }
    

}
