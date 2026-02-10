<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Seat;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $events = Event::with(['organizer', 'venue', 'eventType'])
            ->where('is_active', true)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();

        return view('events.index', compact('events'));
    }

    //Public function show($id)
    //
    //   $event = Event::with([
    //       'organizer',
    //       'venue',
    //       'showtimes.seats.reservations'
    //   ])->findOrFail($id);
//
    //   $seats = collect();
    //   if ($event->isCinema() && $event->showtimes->isNotEmpty()) {
    //       $seats = $event->showtimes->first()->seats->map(function ($seat) {
    //           return [
    //               'row_label'   => $seat->row_label,
    //               'seat_number' => $seat->seat_number,
    //               'status'      => $seat->reservations->isNotEmpty() ? 'reserved' : 'free',
    //           ];
    //       });
    //   }
//
    //    return view('events.show', compact('event', 'seats'));
    //}

    public function show($id)
{
    $event = Event::with(['organizer','venue'])
        ->findOrFail($id);

    // récupérer le champ seats (string)
    $reservedSeats = Reservation::where('event_id', $id)
        ->whereNotNull('seats')
        ->pluck('seats')
        ->toArray();

    // transformer en tableau propre
    $reservedSeats = collect($reservedSeats)
        ->flatMap(fn($s) => explode(',', $s))
        ->unique()
        ->values();

    return view('events.show', compact('event', 'reservedSeats'));
}



    public function cinema()
    {
        $events = Event::with(['organizer','venue','eventType'])
            ->where('is_active', true)
            ->where('category', 'cinema')
            ->get();

        return view('events.index', compact('events'));
    }

    public function libre()
    {
        $events = Event::with(['organizer','venue','eventType'])
            ->where('is_active', true)
            ->where('category', 'libre')
            ->get();

        return view('events.index', compact('events'));
    }

    public function reserve(Request $request, $id)
{
    $event = Event::findOrFail($id);
    $user  = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Validation
    $rules = [
        'method' => 'required|in:mobile_money,cash',
        'phone' => [
            'required_if:method,mobile_money',
            'regex:/^(?:\+261|0)(32|33|34|38)[0-9]{7}$/'
        ],
    ];
    if ($event->isCinema()) {
        $rules['seats'] = 'required|string';
    }

    $request->validate($rules);

    // Créer la commande
    $order = $user->orders()->where('status', 'pending')->first();
    if (!$order) {
        $order = Order::create([
            'user_id'        => $user->id,
            'status'         => 'pending',
            'total_amount'   => 0,
            'payment_method' => $request->payment_method,
        ]);
    }

    $seatIds = [];
    $tickets = [];

    if ($event->isCinema()) {
        $seats = explode(',', $request->seats);
        foreach ($seats as $seatCode) {
            $parts = explode('-', $seatCode);
            $row   = $parts[1] ?? null;
            $num   = $parts[2] ?? null;

            $seat = Seat::where('row_label', $row)
                        ->where('seat_number', $num)
                        ->first();

            if (!$seat) continue;

            $ticket = Ticket::create([
                'order_id'    => $order->id,
                'event_id'    => $event->id,
                'showtime_id' => $event->showtimes->first()->id ?? null,
                'seat_id'     => $seat->id,
                'price'       => $event->ticket_price,
                'qr_code'     => Str::uuid(),
                'status'      => 'unused',
            ]);

            $order->increment('total_amount', $event->ticket_price);
            $seatIds[] = $seat->id;
            $tickets[$seat->id] = $ticket->id;
        }
    } else {
        $ticket = Ticket::create([
            'order_id'    => $order->id,
            'event_id'    => $event->id,
            'showtime_id' => null,
            'seat_id'     => null,
            'price'       => $event->ticket_price,
            'qr_code'     => Str::uuid(),
            'status'      => 'unused',
        ]);
        $order->increment('total_amount', $event->ticket_price);
    }

    // Créer la réservation
    $reservation = Reservation::create([
        'user_id'     => $user->id,
        'event_id'    => $event->id,
        'quantity'    => $event->isCinema() ? max(1, count($seatIds)) : 1,
        'status'      => 'confirmée',
        'reserved_at' => now(),
        'seats'       => $event->isCinema() ? $request->seats : null,
    ]);

    if (!empty($seatIds)) {
        foreach ($seatIds as $seatId) {
            $reservation->seats()->attach($seatId, [
                'showtime_id' => $event->showtimes->first()->id ?? null,
                'ticket_id'   => $tickets[$seatId] ?? null,
                'reserved_at' => now(),
            ]);
        }
    }

    // Paiement via Efaina
  //  if ($request->payment_method === 'mobile_money') {
        // Déterminer l’opérateur en fonction du numéro
 //       $prefix = substr($request->phone, 3, 2); // ex: +26134 → "34"
   //     $operator = match($prefix) {
     //       '34' => 'mvola',
       //     '33' => 'orange_money',
         //   '32' => 'airtel_money',
          //  default => 'mvola',
       // };

       // app(\App\Services\EfainaService::class)->pay(
        //    $order->total_amount,
        //    $request->phone,
        //    $operator,
        //    $order->id
       // );
   // }

    //return redirect()->route('reservations.index')
      //  ->with('success', 'Réservation enregistrée avec les sièges sélectionnés.');
//}
// Paiement via Efaina
if ($request->payment_method === 'mobile_money') {
    // Déterminer l’opérateur en fonction du numéro
    $prefix = substr($request->phone, 3, 2); // ex: +26134 → "34"
    $operator = match($prefix) {
        '34' => 'mvola',
        '33' => 'orange_money',
        '32' => 'airtel_money',
        default => 'mvola',
    };

    $efaina = app(\App\Services\EfainaService::class);
    $result = $efaina->pay(
        $order->total_amount,
        $request->phone,
        $operator,
        $order->id
    );

    if ($result['success'] && $result['checkout_url']) {
        // Redirection vers la page de paiement Efaina
        return redirect()->away($result['checkout_url']);
    }

    return back()->withErrors('Impossible de créer le paiement Efaina');
}

// Si paiement cash
return redirect()->route('reservations.index')
    ->with('success', 'Réservation enregistrée avec les sièges sélectionnés.');

}
}