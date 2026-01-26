<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

public function index()
{
    $user = Auth::user();

    // Événements disponibles
    $availableEvents = Event::where('is_active', true)->orderBy('start_date')->get();

    // Réservations du client
    $reservations = Reservation::with('event')
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    // Réservations par mois
    $reservationsByMonth = Reservation::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->where('user_id', $user->id)
        ->groupBy('month')
        ->pluck('count', 'month');

    // Répartition par type d’événement
    $eventTypes = Reservation::with('event')
        ->where('user_id', $user->id)
        ->get()
        ->groupBy(fn($r) => $r->event->type ?? 'Inconnu')
        ->map(fn($group) => $group->count());

    // ✅ Nombre total de billets achetés
    $totalTickets = Ticket::whereHas('order', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->count();

    // ✅ Billets achetés par mois
    $ticketsByMonth = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->whereHas('order', fn($q) => $q->where('user_id', $user->id))
        ->groupBy('month')
        ->pluck('count', 'month');

    // Nombre total de réservations
    $totalReservations = $reservations->count();

    return view('dashboard', compact(
        'availableEvents',
        'reservations',
        'reservationsByMonth',
        'eventTypes',
        'totalTickets',
        'totalReservations',
        'ticketsByMonth' // ✅ Ajouté ici
    ));
}
}