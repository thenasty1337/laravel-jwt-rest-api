<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExchangeRateService;

class UpdateExchangeRates extends Command
{
    protected $signature = 'exchange:update-rates';
    protected $description = 'Update exchange rates from external API';

    protected $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        parent::__construct();
        $this->exchangeRateService = $exchangeRateService;
    }

    public function handle()
    {
        $this->exchangeRateService->updateRates();
        $this->info('Exchange rates updated successfully.');
    }
}
