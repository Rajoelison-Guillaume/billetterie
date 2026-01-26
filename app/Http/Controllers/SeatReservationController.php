<?php

namespace App\Http\Controllers;

use App\Models\SeatReservation;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SeatReservationController extends Controller
{
    /** Affiche toutes les réservations (admin uniquement) */
    public function index()
    {
        $this->authorize('is_admin'); // middleware ou policy
        $reservations = SeatReservation::with(['showtime','seat','ticket'])->paginate(15);
        return view('reservations.index', compact('reservations'));
    }

    /** Affiche une réservation spécifique (admin uniquement) */
    public function show($id)
    {
        $this->authorize('is_admin');
        $reservation = SeatReservation::with(['showtime','seat','ticket'])->findOrFail($id);
        return view('reservations.show', compact('reservation'));
    }

    /** Affiche les sièges disponibles pour une séance (client) */
    public function seats($showtimeId)
    {
        $showtime = Showtime::with('room.seats')->findOrFail($showtimeId);
        $reservedSeats = SeatReservation::where('showtime_id', $showtimeId)->pluck('seat_id')->toArray();

        return view('reservations.seats', compact('showtime', 'reservedSeats'));
    }

    /** Réserver une ou plusieurs places et générer les billets (client) */
    public function reserve(Request $request, $showtimeId)
    {
        $request->validate([
            'seat_id' => 'required|array',
            'seat_id.*' => 'exists:seats,id',
        ]);

        $user = Auth::user();
        $showtime = Showtime::with('event')->findOrFail($showtimeId);

        // Créer ou récupérer la commande active (panier)
        $order = $user->orders()->where('status','pending')->first();
        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => 0,
            ]);
        }

        $total = 0;

        foreach ($request->seat_id as $seatId) {
            // Vérifier si la place est déjà réservée
            if (SeatReservation::where('showtime_id',$showtimeId)->where('seat_id',$seatId)->exists()) {
                continue; // ignorer les places déjà prises
            }

            // Créer le billet
            $ticket = Ticket::create([
                'order_id'    => $order->id,
                'event_id'    => $showtime->event_id,
                'showtime_id' => $showtime->id,
                'seat_id'     => $seatId,
                'price'       => $showtime->price,
                'qr_code'     => uniqid('QR-'),
                'status'      => 'unpaid',
            ]);

            // Créer la réservation de siège
            SeatReservation::create([
                'seat_id'     => $seatId,
                'showtime_id' => $showtimeId,
                'ticket_id'   => $ticket->id,
                'reserved_at' => now(),
            ]);

            $total += $ticket->price;
        }

        // Mettre à jour le total de la commande
        $order->total_amount += $total;
        $order->save();

        return redirect()->route('orders.cart')->with('success','Places réservées et ajoutées au panier !');
    }
}
