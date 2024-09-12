<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CryptoTransaction;
use App\Models\Wallet;
use App\Services\CryptoApisService;

class WebhookController extends Controller
{
    public function handleTransaction(Request $request)
    {
        $transaction = $request->all();
        $service = new CryptoApisService();
        $service->handleIncomingTransaction($transaction);

        return response()->json(['status' => 'success']);
    }
}
