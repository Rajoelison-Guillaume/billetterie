<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationSeat;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeatReservationController extends Controller
{
    public function seats($eventId)
    {
        $event = Event::with('room.seats')->findOrFail($eventId);
        if (!$event->isCinema() || !$event->room) {
            abort(404, 'Événement non cinéma ou sans salle.');
        }
        $reservedSeats = ReservationSeat::whereHas('reservation', fn($q) => $q->where('event_id', $eventId))
            ->pluck('seat_id')
            ->toArray();

        return view('reservations.seats', compact('event', 'reservedSeats'));
    }

    public function reserve(Request $request, $eventId)
    {
        $request->validate([
            'seat_id' => 'required|array',
            'seat_id.*' => 'exists:seats,id',
        ]);

        $user = Auth::user();
        $event = Event::findOrFail($eventId);
        if (!$event->isCinema()) {
            return back()->withErrors(['error' => 'Réservation de sièges réservée aux événements cinéma.']);
        }

        $order = $user->orders()->where('status', 'pending')->first();
        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => 0,
            ]);
        }

        DB::transaction(function () use ($request, $user, $event, $order) {
            $reservation = Reservation::create([
                'user_id'     => $user->id,
                'event_id'    => $event->id,
                'room_id'     => $event->room_id,
                'quantity'    => count($request->seat_id),
                'status'      => 'pending',
                'reserved_at' => now(),
            ]);

            $total = 0;
            foreach ($request->seat_id as $seatId) {
                $already = ReservationSeat::whereHas('reservation', fn($q) => $q->where('event_id', $event->id))
                            ->where('seat_id', $seatId)->exists();
                if ($already) continue;

                $ticket = Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'seat_id'  => $seatId,
                    'price'    => $event->ticket_price,
                    'qr_code'  => uniqid('QR-'),
                    'status'   => 'unpaid',
                ]);

                ReservationSeat::create([
                    'reservation_id' => $reservation->id,
                    'seat_id'        => $seatId,
                    'ticket_id'      => $ticket->id,
                    'reserved_at'    => now(),
                ]);

                $total += $ticket->price;
            }

            $order->total_amount += $total;
            $order->save();
        });

        return redirect()->route('orders.cart')->with('success', 'Places réservées et ajoutées au panier.');
    }
}