<?php
namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Wallet;
use App\Models\CryptoTransaction;
use App\Models\CryptocurrencyBlockchain;

class CryptoApisService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.crypto_apis.api_key');
        $this->client = new Client([
            'base_uri' => 'https://api.cryptoapis.io/v1/',
            'headers' => [
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function createDepositAddress($walletId, $blockchain, $network, $userId, $currencyId)
    {
        $response = $this->client->post("wallet-as-a-service/wallets/{$walletId}/{$blockchain}/{$network}/addresses", [
            'json' => [
                'context' => 'yourExampleContext',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['data']['item']['address'])) {
            Wallet::create([
                'user_id' => $userId,
                'currency_id' => $currencyId,
                'currency' => $blockchain,
                'deposit_address' => $data['data']['item']['address'],
            ]);

            return $data['data']['item']['address'];
        }

        return null;
    }

    public function monitorAddress($address, $callbackUrl)
    {
        $response = $this->client->post('webhook/endpoint', [
            'json' => [
                'event' => 'address',
                'address' => $address,
                'url' => $callbackUrl,
            ],
        ]);

        return $response->getStatusCode() === 200;
    }

    public function handleIncomingTransaction($transaction)
    {
        $address = $transaction['data']['item']['address'];
        $amount = $transaction['data']['item']['amount'];
        $hash = $transaction['data']['item']['transactionId'];

        $wallet = Wallet::where('deposit_address', $address)->first();
        if ($wallet) {
            CryptoTransaction::create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->uuid,
                'currency' => $wallet->currency,
                'amount' => $amount,
                'transaction_hash' => $hash,
            ]);

            // Update wallet balance or other actions...
            $wallet->balance += $amount;
            $wallet->save();
        }
    }
}
