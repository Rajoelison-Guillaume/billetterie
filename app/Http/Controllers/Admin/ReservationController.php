<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        // Charger les relations nécessaires
        $reservations = Reservation::with(['user','event','seats'])
            ->latest()
            ->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = Reservation::with(['user','event','seats'])
            ->findOrFail($id);

        return view('admin.reservations.show', compact('reservation'));
    }
}
