<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PapiService
{
    /**
     * Initier un paiement via Papi.mg
     *
     * @param float $amount
     * @param string $phone
     * @param string $provider ("mvola", "orange_money", "airtel_money")
     * @param int $orderId
     * @return array
     */
    public function pay($amount, $phone, $provider, $orderId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAPI_API_KEY'),
        ])->post(env('PAPI_API_URL') . '/payments', [
            'amount' => $amount,
            'currency' => 'MGA',
            'provider' => $provider,
            'phone' => $phone,
            'reference' => 'ORDER-' . $orderId,
            'description' => 'Paiement Billetterie MG',
        ]);

        return $response->json();
    }
}
