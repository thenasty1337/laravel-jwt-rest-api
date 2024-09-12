<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $wallets = Wallet::where('user_id', $request->user()->uuid)->get();
        return response()->json($wallets);
    }

    public function deposit(Request $request)
    {
        // Implement deposit logic
    }

    public function withdraw(Request $request)
    {
        // Implement withdrawal logic
    }
}
