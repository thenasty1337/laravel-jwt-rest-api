<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeRate;


class ExchangeRateController extends Controller
{
    public function index()
    {
        $exchangeRates = ExchangeRate::all();
        return response()->json($exchangeRates);
    }
}
