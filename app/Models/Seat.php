<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seat extends Model
{
    protected $fillable = [
        'room_id',
        'row_label',
        'seat_number',
        'is_accessible',
    ];

    protected $casts = [
        'is_accessible' => 'boolean',
    ];

    /**
     * Salle à laquelle le siège appartient
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Réservations associées à ce siège
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(SeatReservation::class);
    }

    /**
     * Ticket associé à ce siège
     */
    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class);
    }

    /**
     * Libellé complet du siège (ex: A12)
     */
    public function label(): string
    {
        return $this->row_label . $this->seat_number;
    }
}
