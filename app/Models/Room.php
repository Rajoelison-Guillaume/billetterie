<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['venue_id','name','capacity','description'];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
