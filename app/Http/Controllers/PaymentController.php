<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /** Liste des paiements (admin) */
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

        // orders() avec parenthèses: on construit la requête
        $order = $user->orders()
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('events.index')->with('error', 'Votre panier est vide.');
        }

        return view('checkout.show', compact('order'));
    }

    /** Traitement du paiement */
    public function pay(Request $request)
    {
        $request->validate([
            'method' => 'required|string', // mvola, orange_money, airtel_money
        ]);

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

        $payment = Payment::create([
            'order_id'     => $order->id,
            'amount'       => $order->total_amount,
            'method'       => $request->string('method'),
            'provider_ref' => uniqid('PAY-'), // à remplacer par la vraie ref du provider
            'status'       => 'success',      // simplifié, à adapter selon API réelle
        ]);

        $order->update(['status' => 'paid']);

        // tickets en propriété (sans parenthèses)
        foreach ($order->tickets as $ticket) {
            $ticket->update(['status' => 'paid']);
        }

        return redirect()->route('tickets.index')->with('success', 'Paiement effectué avec succès !');
    }
}
