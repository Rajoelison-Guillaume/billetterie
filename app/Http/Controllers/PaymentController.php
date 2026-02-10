<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        $user = Auth::user();
        $order = $user->orders()->where('status', 'pending')->latest()->first();

        if (!$order) {
            return redirect()->route('events.index')->with('error', 'Votre panier est vide.');
        }

        $ticket = $order->tickets()->first();
        $event  = $ticket->event;
        $phone  = $user->phone;

        // ⚡ Appel réel à Efaina
        $result = app(\App\Services\EfainaService::class)->pay(
            $order->total_amount,
            $phone,
            $request->method,
            $order->id
        );

        if (!$result['success']) {
            return back()->with('error', 'Échec du paiement Efaina : ' . ($result['body'] ?? ''));
        }

        // ✅ Créer un paiement local en "pending"
        Payment::create([
            'order_id'     => $order->id,
            'amount'       => $order->total_amount,
            'method'       => $request->method,
            'provider'     => 'efaina',
            'provider_ref' => $result['reference'] ?? null,
            'status'       => 'pending', // sera mis à jour par le webhook
        ]);

        // ✅ Mettre la commande en attente
        $order->update(['status' => 'pending']);

        // ✅ Gestion des tickets
        if ($event->isCinema()) {
            // Cas cinéma → il y a des sièges
            if ($request->seat_id) {
                $seat = Seat::findOrFail($request->seat_id);

                if ($seat->ticket && $seat->ticket->status === 'paid') {
                    return back()->with('error', 'Ce siège est déjà réservé.');
                }

                $ticket->update([
                    'seat_id' => $seat->id,
                    'status'  => 'pending', // confirmé par webhook
                ]);
            } else {
                $ticket->update(['status' => 'pending']);
            }
        } else {
            // Cas événement libre → pas de siège
            $ticket->update(['status' => 'pending']);
        }

        // ✅ Rediriger vers la page de checkout Efaina
        if (!empty($result['checkout_url'])) {
            return redirect()->away($result['checkout_url']);
        }

        return redirect()->route('tickets.index')->with('success', 'Paiement initié, en attente de confirmation Efaina.');
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
