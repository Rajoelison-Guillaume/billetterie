<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\SeatReservation;
use App\Models\Venue;
use App\Models\EventType;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            // Compteurs globaux
            'eventsCount'      => Event::count(),
            'activeEvents'     => Event::where('start_date', '>=', now())->count(),
            'ordersCount'      => Order::count(),
            'paidOrders'       => Order::where('status', 'paid')->count(),
            'ticketsCount'     => Ticket::count(),
            'reservationsCount'=> SeatReservation::count(),

            // Données pour les graphiques
            'ticketsByEvent'   => Event::with(['tickets'])->get(),
            'eventsByVenue'    => Venue::withCount('events')->get(),
            'eventsByType'     => EventType::withCount('events')->get(),
        ]);
    }
    public function stats()
    {
    $ticketsByEvent = \App\Models\Event::with('tickets')->get()->map(function ($event) {
        return [
            'title' => $event->title,
            'tickets' => $event->tickets->count()
        ];
    });

    $eventsByVenue = \App\Models\Venue::withCount('events')->get()->map(function ($venue) {
        return [
            'name' => $venue->name,
            'count' => $venue->events_count
        ];
    });

    $eventsByType = \App\Models\EventType::withCount('events')->get()->map(function ($type) {
        return [
            'name' => $type->name ?? 'Type inconnu',
            'count' => $type->events_count
        ];
    });

    return response()->json([
        'ticketsByEvent' => $ticketsByEvent,
        'eventsByVenue' => $eventsByVenue,
        'eventsByType' => $eventsByType
    ]);
}

}
