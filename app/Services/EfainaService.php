<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EfainaService
{
    /**
     * Initier un paiement via Efaina.mg
     *
     * @param float $amount
     * @param string $phone
     * @param string $provider ("mvola", "orange_money", "airtel_money")
     * @param string $reference  Référence unique de la commande
     * @return array
     */
    public function pay($amount, $phone, $provider, $reference)
    {
        $apiUrl = env('EFAINA_API_URL', 'https://api.efaina.mg');
        $apiKey = env('EFAINA_API_KEY');

        $payload = [
            'amount'      => $amount,
            'currency'    => 'MGA',
            'provider'    => $provider,
            'phone'       => $phone,
            'reference'   => $reference,
            'description' => 'Paiement Billetterie MG',
            'callback_url'=> route('webhook.efaina'), // webhook Laravel
        ];

        Log::info('Paiement Efaina envoyé', $payload);

        $response = Http::withToken($apiKey)->post($apiUrl . '/payments', $payload);

        if ($response->failed()) {
            Log::error('Paiement Efaina échoué', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        }

        return $response->json();
    }
}
