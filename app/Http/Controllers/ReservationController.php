<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SessionSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        // Base validation
        $request->validate([
            'event_id'       => 'required|integer',
            'showtime_id'    => 'required|integer',
            'seat_id'        => 'required|integer',
            'price'          => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,mobile_money',
            'provider'       => 'nullable|in:MVola,OrangeMoney,AirtelMoney',
            'provider_ref'   => 'nullable|string',
        ]);

        // Extra rules: require provider/provider_ref when mobile_money
        if ($request->payment_method === 'mobile_money') {
            $request->validate([
                'provider'     => 'required|in:MVola,OrangeMoney,AirtelMoney',
                'provider_ref' => 'required|string',
            ]);
        }

        return DB::transaction(function () use ($request) {

            // Lock the seat row for this showtime to avoid race conditions
            $sessionSeat = SessionSeat::where('showtime_id', $request->showtime_id)
                ->where('seat_id', $request->seat_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($sessionSeat->status === 'reserved') {
                return back()->withErrors(['seat' => 'Ce siège est déjà réservé.']);
            }

            // Create order
            $order = Order::create([
                'user_id'        => Auth::id(), // or $request->user()->id
                'total_amount'   => $request->price,
                'status'         => $request->payment_method === 'cash' ? 'pending' : 'paid',
                'payment_method' => $request->payment_method,
            ]);

            // Create ticket with unique QR
            $ticket = Ticket::create([
                'order_id'    => $order->id,
                'event_id'    => $request->event_id,
                'showtime_id' => $request->showtime_id,
                'seat_id'     => $request->seat_id,
                'price'       => $request->price,
                'qr_code'     => (string) Str::uuid(),
                'status'      => 'unused',
            ]);

            // Mark seat as reserved
            $sessionSeat->update([
                'status'      => 'reserved',
                'reserved_at' => now(),
            ]);

            // Record payment
            Payment::create([
                'order_id'     => $order->id,
                'amount'       => $request->price,
                'method'       => $request->payment_method,
                'provider'     => $request->payment_method === 'mobile_money' ? $request->provider : null,
                'provider_ref' => $request->payment_method === 'mobile_money' ? $request->provider_ref : null,
                'status'       => $request->payment_method === 'cash' ? 'pending' : 'paid',
            ]);

            // Redirect to an existing route: adjust if your route name differs
            return redirect()
                ->route('tickets.show', $ticket->id) // use 'tickets.show' if 'client.tickets.show' doesn’t exist
                ->with('success', 'Réservation confirmée. Votre billet est prêt.');
        });
    }
}
