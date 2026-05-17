<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['event.venue', 'reservationSeats.seat', 'tickets'])
            ->where('user_id', Auth::id())
            ->orderByDesc('reserved_at')
            ->get();

        $reservedSeatsByEvent = [];
        foreach ($reservations as $reservation) {
            $reservedSeatsByEvent[$reservation->event_id] = $reservation->reservationSeats
                ->map(fn($rs) => "seat-{$rs->seat->row_label}-{$rs->seat->seat_number}")
                ->toArray();
        }

        return view('reservations.index', compact('reservations', 'reservedSeatsByEvent'));
    }

    public function show($id)
    {
        $reservation = Reservation::with(['event.venue', 'event.organizer', 'tickets', 'reservationSeats.seat'])
            ->findOrFail($id);
        $reservedSeats = $reservation->reservationSeats
            ->map(fn($rs) => "seat-{$rs->seat->row_label}-{$rs->seat->seat_number}")
            ->toArray();

        return view('reservations.show', compact('reservation', 'reservedSeats'));
    }
}