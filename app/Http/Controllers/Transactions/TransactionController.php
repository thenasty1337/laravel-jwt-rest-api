<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->uuid)->get();
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        // Implement transaction creation logic
    }
}
