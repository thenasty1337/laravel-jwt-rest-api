<?php
namespace App\Services;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction($user_id, $wallet_id, $currency_id, $amount, $transaction_type)
    {
        DB::transaction(function () use ($user_id, $wallet_id, $currency_id, $amount, $transaction_type) {
            $wallet = Wallet::where('user_id', $user_id)->where('uuid', $wallet_id)->first();
            $currency = $wallet->currency;

            if ($transaction_type == 'deposit') {
                $wallet->balance += $amount;
            } elseif ($transaction_type == 'withdrawal') {
                if ($wallet->balance < $amount) {
                    throw new \Exception('Insufficient balance');
                }
                $wallet->balance -= $amount;
            }

            $wallet->save();

            Transaction::create([
                'user_id' => $user_id,
                'wallet_id' => $wallet_id,
                'currency_id' => $currency_id,
                'amount' => $amount,
                'transaction_type' => $transaction_type,
                'status' => 'completed',
            ]);
        });
    }
}
