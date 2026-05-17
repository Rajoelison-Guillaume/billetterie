<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EfainaService
{
    protected string $apiUrl;
    protected string $apiToken;
    protected string $walletId;

    public function __construct()
    {
        // ⚡ Sandbox par défaut
        $this->apiUrl   = rtrim(config('services.efaina.url', 'https://sandback.efaina.com/api/public/v1'), '/');
        $this->apiToken = config('services.efaina.token');
        $this->walletId = config('services.efaina.wallet_id');
    }

    /**
     * Crée un checkout Efaina
     */
/**  *
 *    public function pay(int $amount, string $phone, string $method, int $orderId): array
    *{
       * $payload = [
        *    'amount'      => $amount,
        *    'comment'     => "Paiement commande #{$orderId}",
        *    'wallet_id'   => $this->walletId,
        *    'company'     => 'Billetterie MG',
        *    'return_urls' => [
         *       'return_to_merchant_url' => route('checkout.success'),
        *        'cancel_url'             => route('checkout.cancel'),
        *    ],
         *   'customer' => [
        *        'phone'    => $phone,
        *        'provider' => $method,
        *    ],
        *];

      *  Log::info('Envoi du paiement Efaina', $payload);

      *  try {
           * $response = Http::withToken($this->apiToken)
          *      ->timeout(30)
         *       ->post("{$this->apiUrl}/pay/create-checkout", $payload);

        *    if ($response->failed()) {
    *            Log::error('Échec du paiement Efaina', [
   *                 'status' => $response->status(),
  *                  'body'   => $response->body(),
 *               ]);
*
     *           return [
     *               'success' => false,
    *                'status'  => $response->status(),
   *                 'body'    => $response->body(),
  *              ];
 *           }
*
      *      $data = $response->json();
*
        *    return [
       *         'success'      => true,
      *          'checkout_url' => $data['data']['checkout_url'] ?? null,
     *           'reference'    => $data['data']['transaction'] ?? null,
       *     ];
    *    } catch (\Exception $e) {
   *         Log::error('Erreur connexion Efaina', [
  *              'message' => $e->getMessage(),
 *           ]);
*
     *       return [
    *            'success' => false,
   *             'status'  => 500,
  *              'body'    => $e->getMessage(),
 *           ];
*        }
*    }
*/


public function pay(int $amount, string $phone, string $method, int $orderId): array
{
    $payload = [
        'amount'      => $amount,
        'comment'     => "Paiement commande #{$orderId}",
        'wallet_id'   => $this->walletId,
        'company'     => 'Billetterie MG',
        'return_urls' => [
            'return_to_merchant_url' => route('checkout.success'),
            'cancel_url'             => route('checkout.cancel'),
        ],
        'customer' => [
            'phone'    => $phone,
            'provider' => $method,
        ],
    ];

    $response = Http::withToken($this->apiToken)
        ->post("{$this->apiUrl}/pay/create-checkout", $payload);

    if ($response->failed()) {
        Log::error('Échec du paiement Efaina', $response->json() ?? []);
        return ['success' => false];
    }

    $data = $response->json('data');

    // Sauvegarde du paiement local
    \App\Models\Payment::create([
        'order_id'     => $orderId,
        'provider'     => 'efaina',
        'provider_ref' => $data['transaction'] ?? null,
        'status'       => 'pending',
        'amount'       => $amount,
    ]);

    return [
        'success'      => true,
        'checkout_url' => $data['checkout_url'] ?? null,
        'reference'    => $data['transaction'] ?? null,
    ];
}

    /**
     * Vérifie une transaction Efaina
     */
    public function verify(string $transactionId): ?array
    {
        try {
            $response = Http::withToken($this->apiToken)
                ->get("{$this->apiUrl}/details-transaction/{$transactionId}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Erreur vérification Efaina', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
