<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $order = $user->orders()->where('status', 'pending')->latest()->first();
        if (!$order) {
            return redirect()->route('events.index')->with('error', 'Panier vide.');
        }
        return view('checkout.show', compact('order'));
    }

    public function pay(Request $request)
    {
        $request->validate(['method' => 'required|string']);

        $user = Auth::user();
        $order = $user->orders()->where('status', 'pending')->latest()->first();
        if (!$order) {
            return redirect()->route('events.index')->with('error', 'Panier vide.');
        }

        $phone = $user->phone;
        if (!$phone) {
            return back()->withErrors(['phone' => 'Veuillez renseigner votre numéro de téléphone dans votre profil.']);
        }

        $result = app(\App\Services\EfainaService::class)->pay(
            $order->total_amount, $phone, $request->method, $order->id
        );

        if (!$result['success']) {
            return back()->with('error', 'Échec du paiement Efaina.');
        }

        Payment::create([
            'order_id'     => $order->id,
            'amount'       => $order->total_amount,
            'method'       => $request->method,
            'provider_ref' => $result['reference'] ?? null,
            'status'       => 'pending',
        ]);

        $order->update(['status' => 'pending']);

        if (!empty($result['checkout_url'])) {
            return redirect()->away($result['checkout_url']);
        }

        return redirect()->route('tickets.index')->with('success', 'Paiement initié.');
    }

    public function success() { return view('checkout.success'); }
    public function cancel()  { return view('checkout.cancel'); }
}