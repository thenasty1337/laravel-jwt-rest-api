<?php
namespace App\Services;

use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    public function updateRates()
    {
        // Fetch real-time exchange rates from an API
        $response = Http::get('https://api.exchangerate-api.com/v4/latest/USD');
        $rates = $response->json()['rates'];

        foreach ($rates as $currency => $rate) {
            ExchangeRate::updateOrCreate(
                ['currency_from' => 'USD', 'currency_to' => $currency],
                ['rate' => $rate]
            );
        }
    }

    public function convert($from, $to, $amount)
    {
        $rate = ExchangeRate::where('currency_from', $from)->where('currency_to', $to)->first()->rate;
        return $amount * $rate;
    }
}
