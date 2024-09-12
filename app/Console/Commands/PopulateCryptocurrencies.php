<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CoinLayerService;

class PopulateCryptocurrencies extends Command
{
    protected $signature = 'crypto:populate';
    protected $description = 'Populate cryptocurrencies from CoinLayer API';

    protected $coinLayerService;

    public function __construct(CoinLayerService $coinLayerService)
    {
        parent::__construct();
        $this->coinLayerService = $coinLayerService;
    }

    public function handle()
    {
        $this->coinLayerService->fetchCryptoList();
        $this->info('Cryptocurrencies populated successfully.');
    }
}
