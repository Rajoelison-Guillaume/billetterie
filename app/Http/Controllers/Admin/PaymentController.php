<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Liste des paiements avec supervision.
     */
    public function index()
    {
        // Charger les paiements avec la commande et l’utilisateur
        $payments = Payment::with(['order.user', 'order.tickets.seat', 'order.tickets.event'])
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Détail d’un paiement spécifique.
     */
    public function show($id)
    {
        $payment = Payment::with(['order.user', 'order.tickets.seat', 'order.tickets.event'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Supervision : marquer un paiement comme échoué (audit).
     */
    public function markAsFailed($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status !== 'failed') {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('admin.payments.index')
            ->with('error', 'Paiement marqué comme échoué par l’admin.');
    }
}
