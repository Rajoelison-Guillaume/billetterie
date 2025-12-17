<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    protected $fillable = [
        'room_id',
        'row_label',
        'seat_number',
        'is_accessible',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(SeatReservation::class);
    }
}
