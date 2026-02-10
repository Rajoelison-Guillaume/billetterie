<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\ReservationSeat;
use App\Models\Venue;
use App\Models\EventType;
use App\Models\Showtime;

class DashboardController extends Controller
{
    public function index()
    {
        // Compteurs globaux
        $eventsCount       = Event::count();
        $activeEvents      = Event::where('start_date', '>=', now())->count();
        $ordersCount       = Order::count();
        $paidOrders        = Order::where('status', 'paid')->count();
        $ticketsCount      = Ticket::count();
        $reservationsCount = ReservationSeat::count();

        // Statistiques cinéma
        $totalReservedSeats = ReservationSeat::count();
        $totalRevenue       = Ticket::sum('price');

        $showtimes = Showtime::with('room.seats')->get();
        $occupancyRates = [];

        foreach ($showtimes as $showtime) {
            $totalSeats    = $showtime->room->seats->count();
            $reservedSeats = ReservationSeat::where('showtime_id', $showtime->id)->count();
            $rate          = $totalSeats > 0 ? ($reservedSeats / $totalSeats) * 100 : 0;
            $occupancyRates[] = $rate;
        }

        $averageOccupancy = count($occupancyRates) > 0
            ? round(array_sum($occupancyRates) / count($occupancyRates), 2)
            : 0;

        // Données pour les graphiques
        $ticketsByEvent = Event::with(['tickets'])->get()->map(function ($event) {
            $event->ticket_price = $event->tickets->avg('price') ?? 0;
            return $event;
        });

        $eventsByVenue = Venue::withCount('events')->get();

        
        // ⚡ Récupérer les types d’événements avec leur nombre d'événements
        $eventsByType = EventType::withCount('events')->get();

        // ✅ Revenus par mois
        $revenueByMonth = Ticket::selectRaw('MONTH(created_at) as month, SUM(price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('admin.dashboard', compact(
            'eventsCount',
            'activeEvents',
            'ordersCount',
            'paidOrders',
            'ticketsCount',
            'reservationsCount',
            'totalReservedSeats',
            'totalRevenue',
            'averageOccupancy',
            'ticketsByEvent',
            'eventsByVenue',
            'eventsByType',
            'revenueByMonth'
        ));
    }

    // Endpoint JSON pour rafraîchissement automatique
    public function stats()
    {
        $ticketsByEvent = Event::with('tickets')->get()->map(function ($event) {
            return [
                'title'        => $event->title,
                'tickets'      => $event->tickets->count(),
                'ticket_price' => $event->tickets->avg('price') ?? 0,
            ];
        });

        $eventsByVenue = Venue::withCount('events')->get()->map(function ($venue) {
            return [
                'name'  => $venue->name,
                'count' => $venue->events_count
            ];
        });

        // ⚡ Filtrer les types null
        $eventsByType = EventType::withCount('events')
            ->whereNotNull('name')
            ->get()
            ->map(function ($type) {
                return [
                    'name'  => $type->name,
                    'count' => $type->events_count
                ];
            });

        return response()->json([
            'ticketsByEvent'     => $ticketsByEvent,
            'eventsByVenue'      => $eventsByVenue,
            'eventsByType'       => $eventsByType,
            'totalReservedSeats' => ReservationSeat::count(),
            'totalRevenue'       => Ticket::sum('price'),
            'averageOccupancy'   => $this->calculateAverageOccupancy(),
        ]);
    }

    private function calculateAverageOccupancy()
    {
        $showtimes = Showtime::with('room.seats')->get();
        $occupancyRates = [];

        foreach ($showtimes as $showtime) {
            $totalSeats    = $showtime->room->seats->count();
            $reservedSeats = ReservationSeat::where('showtime_id', $showtime->id)->count();
            $rate          = $totalSeats > 0 ? ($reservedSeats / $totalSeats) * 100 : 0;
            $occupancyRates[] = $rate;
        }

        return count($occupancyRates) > 0
            ? round(array_sum($occupancyRates) / count($occupancyRates), 2)
            : 0;
    }
}
