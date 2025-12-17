<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeatReservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = SeatReservation::with(['user','showtime','seat'])
            ->latest()
            ->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = SeatReservation::with(['user','showtime','seat'])
            ->findOrFail($id);

        return view('admin.reservations.show', compact('reservation'));
    }
}
