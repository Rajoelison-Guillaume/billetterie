<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'organizer_id',
        'venue_id',
        'room_id',
        'event_type_id',
        'category',
        'title',
        'slug',
        'description',
        'image_path',
        'trailer_url',
        'start_date',
        'end_date',
        'ticket_price',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function organizer()     { return $this->belongsTo(Organizer::class); }
    public function venue()         { return $this->belongsTo(Venue::class); }
    public function room()          { return $this->belongsTo(Room::class); }
    public function eventType()     { return $this->belongsTo(EventType::class); }
    public function showtimes()     { return $this->hasMany(Showtime::class); }
    public function tickets() 
    { 
        return $this->hasMany(Ticket::class); 
    }
    public function isCinema(): bool
    {
        return $this->category === 'cinema';
    }

    public function isLibre(): bool
    {
        return !$this->isCinema();
    }
}
