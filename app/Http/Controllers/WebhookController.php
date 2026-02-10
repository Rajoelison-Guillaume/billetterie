<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook Efaina reçu', $request->all());

        $transactionId = $request->input('transaction');
        $status        = $request->input('status'); // success, pending, failed

        $payment = Payment::where('provider_ref', $transactionId)->first();

        if ($payment) {
            $payment->update(['status' => $status]);

            $order = $payment->order;
            if ($status === 'success') {
                $order->update(['status' => 'paid']);
                $order->tickets()->update(['status' => 'paid']);
            } elseif ($status === 'failed') {
                $order->update(['status' => 'failed']);
            }
        }

        return response()->json(['message' => 'Webhook traité']);
    }
}
