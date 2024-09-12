<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CoinLayerService;

class UpdateCryptoExchangeRates extends Command
{
    protected $signature = 'crypto-exchange:update-rates';
    protected $description = 'Update cryptocurrency exchange rates from CoinLayer API';

    protected $coinLayerService;

    public function __construct(CoinLayerService $coinLayerService)
    {
        parent::__construct();
        $this->coinLayerService = $coinLayerService;
    }

    public function handle()
    {
        $this->coinLayerService->fetchExchangeRates();
        $this->info('Cryptocurrency exchange rates updated successfully.');
    }
}
