<?php

namespace App\Services;

use GuzzleHttp\Client;

class CoinLayerService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.crypto_apis.api_key');
        $this->client = new Client([
            'base_uri' => 'https://rest.cryptoapis.io/',
            'headers' => [
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function getSupportedAssets()
    {
        $response = $this->client->get('market-data/assets/supported');
        $data = json_decode($response->getBody()->getContents(), true);

        return $data['data']['items'] ?? [];
    }

    public function getSupportedTokens($blockchain, $network)
    {
        $response = $this->client->get("wallet-as-a-service/info/{$blockchain}/{$network}/supported-tokens");
        $data = json_decode($response->getBody()->getContents(), true);

        return $data['data']['items'] ?? [];
    }

    public function getLiveExchangePrice($assetId)
    {
        $response = $this->client->get("market-data/assets/assetId/{$assetId}");
        $data = json_decode($response->getBody()->getContents(), true);

        return $data['data']['item']['latestRate'] ?? null;
    }

    protected function getAssetDetails($assetId)
    {
        $response = $this->client->get("market-data/assets/assetId/{$assetId}");
        return json_decode($response->getBody()->getContents(), true)['data']['item'];
    }
}
