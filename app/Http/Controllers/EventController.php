<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\ReservationSeat;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['venue','organizer','eventType','room'])
            ->where('is_active', true);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('date_start')) {
            $query->whereDate('start_date', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('start_date', '<=', $request->date_end);
        }

        $events = $query->orderBy('start_date')->paginate(12);
        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::with(['organizer','venue','eventType','room.seats'])->findOrFail($id);

        $reservedSeatIds = ReservationSeat::whereHas('reservation', fn($q) =>
            $q->where('event_id', $event->id)->whereIn('status',['pending','confirmée'])
        )->pluck('seat_id')->toArray();

        return view('events.show', compact('event','reservedSeatIds'));
    }

    public function reserve(Request $request, $id)
    {
        $event = Event::with('room.seats','eventType')->findOrFail($id);

        if ($event->isCinema()) {
            $request->validate([
                'seats'  => 'required|string',
                'method' => 'required|string',
                'phone'  => 'required|string|max:30',
            ]);

            $seatIds = array_filter(array_map('intval', explode(',', $request->seats)));

            $alreadyReserved = ReservationSeat::whereHas('reservation', fn($q) =>
                $q->where('event_id', $event->id)->whereIn('status',['pending','confirmée'])
            )->whereIn('seat_id', $seatIds)->exists();

            if ($alreadyReserved) {
                return back()->withErrors(['seats' => 'Un ou plusieurs sièges sont déjà réservés.']);
            }

            DB::transaction(function () use ($event, $seatIds, $request) {
                $total = $event->ticket_price * count($seatIds);

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_amount' => $total,
                    'status' => 'pending',
                ]);

                $reservation = Reservation::create([
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'quantity' => count($seatIds),
                    'status' => 'pending',
                    'reserved_at' => now(),
                    'seats' => implode(',', $seatIds),
                ]);

                foreach ($seatIds as $seatId) {
                    $ticket = Ticket::create([
                        'order_id' => $order->id,
                        'event_id' => $event->id,
                        'seat_id'  => $seatId,
                        'price'    => $event->ticket_price,
                        'qr_code'  => strtoupper(Str::random(14)),
                        'status'   => 'unpaid',
                    ]);

                    ReservationSeat::create([
                        'reservation_id' => $reservation->id,
                        'seat_id'        => $seatId,
                        'ticket_id'      => $ticket->id,
                        'reserved_at'    => now(),
                    ]);
                }
            });

            return redirect()->route('checkout.show')->with('success','Réservation cinéma effectuée.');
        }

        // Événement libre
        $request->validate([
            'quantity' => 'required|integer|min:1|max:20',
            'method'   => 'required|string',
            'phone'    => 'required|string|max:30',
        ]);

        DB::transaction(function () use ($request, $event) {
            $total = $request->quantity * $event->ticket_price;

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            $reservation = Reservation::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'event_id' => $event->id,
                'quantity' => $request->quantity,
                'status' => 'pending',
                'reserved_at' => now(),
            ]);

            for ($i = 0; $i < $request->quantity; $i++) {
                Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'price'    => $event->ticket_price,
                    'qr_code'  => strtoupper(Str::random(14)),
                    'status'   => 'unpaid',
                ]);
            }
        });

        return redirect()->route('checkout.show')->with('success','Réservation libre effectuée.');
    }
}
