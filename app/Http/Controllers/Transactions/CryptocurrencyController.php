<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;

class CryptocurrencyController extends Controller
{
    public function index()
    {
        $cryptocurrencies = Cryptocurrency::all();
        return response()->json($cryptocurrencies);
    }
}
