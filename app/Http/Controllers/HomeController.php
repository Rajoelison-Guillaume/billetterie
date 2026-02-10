<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\ReservationSeat;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Statistiques globales
        $eventsCount = Event::count();
        $ticketsCount = Ticket::count();
        $organizersCount = Organizer::count();

        // Statistiques personnelles
        $totalTickets = 0;
        $totalReservations = 0;
        $reservations = collect();
        $orders = collect();

        if (Auth::check()) {
            $user = Auth::user();

            $totalTickets = Ticket::whereHas('order', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

            $totalReservations = ReservationSeat::whereHas('ticket.order', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

            $reservations = ReservationSeat::with(['showtime.event','seat','ticket'])
                ->whereHas('ticket.order', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->orderByDesc('reserved_at')
                ->get();

            $orders = Order::with(['tickets.event','tickets.seat'])
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        // Événements disponibles
        $availableEvents = Event::where('start_date', '>=', now())->take(6)->get();

        // Organisateurs en vedette
        $featuredOrganizers = Organizer::has('events')->take(3)->get();

        // Section cinéma
        $cinemaEvents = Event::where('category', 'cinema')->take(3)->get();

        // Graphiques
        $reservationsByMonth = ReservationSeat::selectRaw('MONTH(reserved_at) as month, COUNT(*) as count')
            ->groupBy('month')->pluck('count','month');

        $ticketsByMonth = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')->pluck('count','month');

        $eventTypes = Event::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')->pluck('count','category');

        return view('dashboard', compact(
            'eventsCount',
            'ticketsCount',
            'organizersCount',
            'totalTickets',
            'totalReservations',
            'reservations',
            'orders',
            'availableEvents',
            'featuredOrganizers',
            'cinemaEvents',
            'reservationsByMonth',
            'ticketsByMonth',
            'eventTypes'
        ));
    }
}
