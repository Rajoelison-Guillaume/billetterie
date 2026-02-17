<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SessionSeat;
use App\Models\ReservationSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Reservation;

class ReservationController extends Controller
{
    /**
     * Liste des réservations de l'utilisateur connecté
     */
    /*public function index()
    {
        $reservations = Reservation::with([
            'reservationSeats.seat',
            'event.venue',
            'tickets.event',
            'tickets.seat',
            'tickets.order.payment'
        ])
        ->where('user_id', Auth::id())
        ->orderByDesc('reserved_at')
        ->get();

        // Récupérer tous les sièges réservés par événement au bon format
        $reservedSeatsByEvent = [];
        foreach ($reservations as $reservation) {
            $reservedSeatsByEvent[$reservation->event_id] = ReservationSeat::where('showtime_id', $reservation->showtime_id)
                ->with('seat')
                ->get()
                ->map(function($resSeat) {
                    return "seat-{$resSeat->seat->row_label}-{$resSeat->seat->seat_number}";
                })
                ->toArray();
        }

        return view('reservations.index', compact('reservations', 'reservedSeatsByEvent'));
    }
        */
    public function index()
{
    $reservations = Reservation::with([
        'reservationSeats.seat',
        'event.venue',
        'tickets.event',
        'tickets.seat',
        'tickets.order.payment'
    ])
    ->where('user_id', Auth::id())
    ->orderByDesc('reserved_at')
    ->get();

    // Récupérer tous les sièges réservés par séance
    $reservedSeatsByEvent = [];
    foreach ($reservations as $reservation) {
        $reservedSeatsByEvent[$reservation->event_id] = ReservationSeat::where('showtime_id', $reservation->showtime_id)
            ->with('seat')
            ->get()
            ->map(function($resSeat) {
                return "seat-{$resSeat->seat->row_label}-{$resSeat->seat->seat_number}";
            })
            ->toArray();
    }

    return view('reservations.index', compact('reservations', 'reservedSeatsByEvent'));
}



    /**
     * Afficher une réservation en détail
     */
 public function show($id)
{
    $reservation = Reservation::with([
        'event.venue',
        'event.organizer',
        'tickets.event',
        'tickets.seat',
        'tickets.order.payment'
    ])->findOrFail($id);

    // Récupérer tous les sièges réservés pour cette séance au format seat-x-y
    $reservedSeats = ReservationSeat::where('showtime_id', $reservation->showtime_id)
        ->with('seat')
        ->get()
        ->map(function($resSeat) {
            return "seat-{$resSeat->seat->row_label}-{$resSeat->seat->seat_number}";
        })
        ->toArray();

    return view('reservations.show', compact('reservation', 'reservedSeats'));
}


    /**
     * Enregistrer une nouvelle réservation
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id'       => 'required|exists:events,id',
            'showtime_id'    => 'required|exists:showtimes,id',
            'seat_id'        => 'required|exists:seats,id',
            'price'          => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,mobile_money',
            'provider'       => 'nullable|in:MVola,OrangeMoney,AirtelMoney',
            'provider_ref'   => 'nullable|string',
        ]);

        if ($request->payment_method === 'mobile_money') {
            $request->validate([
                'provider'     => 'required|in:MVola,OrangeMoney,AirtelMoney',
                'provider_ref' => 'required|string',
            ]);
        }

        return DB::transaction(function () use ($request) {
            // ⚡ Verrouiller le siège
            $sessionSeat = SessionSeat::where('showtime_id', $request->showtime_id)
                ->where('seat_id', $request->seat_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($sessionSeat->status === 'reserved') {
                return back()->withErrors(['seat' => 'Ce siège est déjà réservé.']);
            }

            // ⚡ Créer la commande
            $order = Order::create([
                'user_id'        => Auth::id(),
                'total_amount'   => $request->price,
                'status'         => $request->payment_method === 'cash' ? 'pending' : 'paid',
                'payment_method' => $request->payment_method,
            ]);

            // ⚡ Créer le ticket
            $ticket = Ticket::create([
                'order_id'    => $order->id,
                'event_id'    => $request->event_id,
                'showtime_id' => $request->showtime_id,
                'seat_id'     => $request->seat_id,
                'price'       => $request->price,
                'qr_code'     => (string) Str::uuid(),
                'status'      => 'unused',
            ]);

            // ⚡ Marquer le siège comme réservé
            $sessionSeat->update([
                'status'      => 'reserved',
                'reserved_at' => now(),
            ]);

            // ⚡ Enregistrer la réservation
            ReservationSeat::create([
                'showtime_id' => $request->showtime_id,
                'seat_id'     => $request->seat_id,
                'ticket_id'   => $ticket->id,
                'reserved_at' => now(),
            ]);

            // ⚡ Enregistrer le paiement
            Payment::create([
                'order_id'     => $order->id,
                'amount'       => $request->price,
                'method'       => $request->payment_method,
                'provider'     => $request->payment_method === 'mobile_money' ? $request->provider : null,
                'provider_ref' => $request->payment_method === 'mobile_money' ? $request->provider_ref : null,
                'status'       => $request->payment_method === 'cash' ? 'pending' : 'paid',
            ]);

            return redirect()
                ->route('reservations.index')
                ->with('success', 'Réservation confirmée. Votre billet est prêt.');
        });
    }
}
