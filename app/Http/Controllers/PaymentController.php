<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seat;
use App\Models\User;

class PaymentController extends Controller
{
    /** Liste des paiements de l’utilisateur */
    public function index()
    {
        $payments = Payment::with('order.user')->paginate(15);
        return view('payments.index', compact('payments'));
    }

    /** Page de checkout (utilisateur) */
    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        /** @var \App\Models\User $user */
        $order = $user->orders()
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('events.index')->with('error', 'Votre panier est vide.');
        }

        return view('checkout.show', compact('order'));
    }

    /** Traitement du paiement + réservation */
    public function pay(Request $request)
{
    $request->validate([
        'method'  => 'required|string',
        'seat_id' => 'nullable|integer',
        'phone'   => 'nullable|string|regex:/^(032|033|034)\d{7}$/',
    ]);

    $user = Auth::user();
    $order = $user->orders()->where('status', 'pending')->latest()->first();

    if (!$order) {
        return redirect()->route('events.index')->with('error', 'Votre panier est vide.');
    }

    $event = $order->tickets()->first()->event;

    $provider = match ($request->method) {
        'mvola'        => 'MVola',
        'orange_money' => 'OrangeMoney',
        'airtel_money' => 'AirtelMoney',
        'cash'         => null,
    };

    $providerRef = 'TX-' . uniqid();
    $status = 'success'; // ou 'pending' si API réelle

    Payment::create([
        'order_id'     => $order->id,
        'amount'       => $order->total_amount,
        'method'       => $request->method,
        'provider'     => $provider,
        'provider_ref' => $providerRef,
        'status'       => $status,
    ]);

    $order->update(['status' => 'paid']);

    $ticket = $order->tickets()->where('status', 'pending')->first();

    if ($event->isCinema()) {
        $seat = Seat::findOrFail($request->seat_id);

        if ($seat->ticket && $seat->ticket->status === 'paid') {
            return back()->with('error', 'Ce siège est déjà réservé.');
        }

        $ticket->update([
            'seat_id' => $seat->id,
            'status'  => 'paid',
        ]);
    } else {
        $ticket->update(['status' => 'paid']);
    }

    return redirect()->route('tickets.index')->with('success', 'Paiement et réservation effectués avec succès !');
}

}
