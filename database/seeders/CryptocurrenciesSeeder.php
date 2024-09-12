<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Services\CoinLayerService;

class CryptocurrenciesSeeder extends Seeder
{
    protected $coinLayerService;

    public function __construct(CoinLayerService $coinLayerService)
    {
        $this->coinLayerService = $coinLayerService;
    }

    public function run()
    {
        $assets = $this->coinLayerService->getSupportedAssets();

        foreach ($assets as $asset) {

            $assetDetails = $this->coinLayerService->getAssetDetails($asset['assetId']);
            DB::table('cryptocurrencies')->insert([
                'uuid' => Str::uuid(),
                'symbol' => $asset['assetSymbol'],
                'name' => $asset['assetName'],
                'name_full' => $asset['assetName'],
                'max_supply' => $assetDetails['maxSupply'] ?? null,
                'icon_url' => $assetDetails['assetLogo']['imageData'] ?? null,
                'usd_rate' => $assetDetails['latestRate']['amount'] ?? null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
