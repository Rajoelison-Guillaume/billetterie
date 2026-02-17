<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationSeat;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user','event','seats'])
            ->latest()
            ->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = Reservation::with([
            'user',
            'event.venue',
            'event.organizer',
            'tickets.seat',
            'tickets.order.user'
        ])->findOrFail($id);

        // ⚡ Récupérer tous les sièges réservés pour la même séance
        $reservedSeats = ReservationSeat::where('showtime_id', $reservation->showtime_id)
            ->with(['seat','ticket.order.user'])
            ->get();

        return view('admin.reservations.show', compact('reservation','reservedSeats'));
    }
}
