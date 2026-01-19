<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Callback appelé par Papi.mg ou Efaina après un paiement.
     */
    public function handle(Request $request)
    {
        // Log pour debug
        Log::info('Webhook reçu', $request->all());

        // Validation des données reçues
        $data = $request->validate([
            'reference'       => 'required|string',   // référence unique de la commande
            'status'          => 'required|string',   // SUCCESS / FAILED
            'amount'          => 'required|numeric',
            'provider'        => 'required|string',   // ex: "Orange Money", "MVola"
            'transaction_id'  => 'nullable|string',
        ]);

        // Récupérer la commande par référence
        $order = Order::where('reference', $data['reference'])->first();
        if (!$order) {
            Log::warning("Webhook : commande introuvable pour référence {$data['reference']}");
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Vérifier si un paiement existe déjà pour éviter les doublons
        $existingPayment = Payment::where('provider_ref', $data['transaction_id'])->first();
        if ($existingPayment) {
            Log::info("Webhook ignoré : paiement déjà enregistré pour transaction {$data['transaction_id']}");
            return response()->json(['message' => 'Payment already processed']);
        }

        // Traitement selon le statut
        if (strtoupper($data['status']) === 'SUCCESS') {
            Payment::create([
                'order_id'     => $order->id,
                'amount'       => $data['amount'],
                'method'       => strtolower($data['provider']),
                'provider'     => ucfirst($data['provider']),
                'provider_ref' => $data['transaction_id'] ?? 'TX-' . uniqid(),
                'status'       => 'success',
            ]);

            $order->update(['status' => 'paid']);
            Log::info("Commande {$order->id} marquée comme payée.");
        } else {
            $order->update(['status' => 'failed']);
            Log::warning("Commande {$order->id} marquée comme échouée.");
        }

        return response()->json(['message' => 'Webhook processed']);
    }
}
