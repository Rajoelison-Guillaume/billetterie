<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Callback appelé par Papi.mg après un paiement.
     */
    public function papiCallback(Request $request)
    {
        // Log pour debug (optionnel)
        Log::info('Webhook Papi reçu', $request->all());

        // Validation minimale des données reçues
        $data = $request->validate([
            'reference'       => 'required',
            'status'          => 'required|string',
            'amount'          => 'required|numeric',
            'provider'        => 'required|string',
            'transaction_id'  => 'nullable|string',
        ]);

        // Récupérer la commande par référence
        $order = Order::find($data['reference']);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Traitement selon le statut
        if ($data['status'] === 'SUCCESS') {
            Payment::create([
                'order_id'     => $order->id,
                'amount'       => $data['amount'],
                'method'       => $data['provider'],
                'provider'     => ucfirst($data['provider']),
                'provider_ref' => $data['transaction_id'] ?? 'TX-' . uniqid(),
                'status'       => 'success',
            ]);

            $order->update(['status' => 'paid']);
        } else {
            $order->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'Webhook processed']);
    }
}
