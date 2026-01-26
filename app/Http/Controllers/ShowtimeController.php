<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Seat;
use App\Models\SeatReservation;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShowtimeController extends Controller
{
    /**
     * Liste toutes les séances
     */
    public function index()
    {
        $showtimes = Showtime::with(['event','room'])->get();
        return view('showtimes.index', compact('showtimes'));
    }

    /**
     * Affiche une séance avec les places libres/occupées
     */
    public function show($id)
    {
        $showtime = Showtime::with(['event','room'])->findOrFail($id);

        $seats = Seat::where('room_id', $showtime->room_id)
            ->orderBy('row_label')
            ->orderBy('seat_number')
            ->get();

        $occupiedSeatIds = SeatReservation::where('showtime_id', $showtime->id)
            ->pluck('seat_id')
            ->toArray();

        return view('showtimes.show', compact('showtime','seats','occupiedSeatIds'));
    }

    /**
     * Réserve une ou plusieurs places pour une séance cinéma
     */
    public function reserve(Request $request, $id)
    {
        $request->validate([
            'seat_id' => 'required|array',
            'seat_id.*' => 'exists:seats,id',
            'payment_method' => 'required|in:mobile_money,cash',
            'phone' => 'required|regex:/^03[2-9][0-9]{7}$/',
        ]);

        $showtime = Showtime::with('event')->findOrFail($id);

        return DB::transaction(function () use ($request, $showtime) {
            $userId = auth()->id();
            $total = 0;
            $tickets = [];

            foreach ($request->seat_id as $seatId) {
                $already = SeatReservation::where('showtime_id', $showtime->id)
                    ->where('seat_id', $seatId)
                    ->lockForUpdate()
                    ->exists();

                if ($already) {
                    continue;
                }

                $total += $showtime->event->ticket_price;

                $tickets[] = [
                    'seat_id' => $seatId,
                    'price' => $showtime->event->ticket_price,
                ];
            }

            if (empty($tickets)) {
                return back()->withErrors(['seat_id' => 'Toutes les places sélectionnées sont déjà réservées.']);
            }

            $order = Order::create([
                'user_id' => $userId,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'phone' => $request->phone,
            ]);

            foreach ($tickets as $data) {
                $ticket = Ticket::create([
                    'order_id' => $order->id,
                    'event_id' => $showtime->event_id,
                    'showtime_id' => $showtime->id,
                    'seat_id' => $data['seat_id'],
                    'price' => $data['price'],
                    'qr_code' => Str::uuid(),
                    'status' => 'unused',
                ]);

                SeatReservation::create([
                    'showtime_id' => $showtime->id,
                    'seat_id' => $data['seat_id'],
                    'ticket_id' => $ticket->id,
                    'reserved_at' => now(),
                ]);
            }

            return redirect()->route('orders.cart')->with('success', 'Places réservées et ajoutées au panier.');
        });
    }
}
    