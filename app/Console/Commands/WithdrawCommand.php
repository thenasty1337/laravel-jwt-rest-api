<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TransactionService;

class WithdrawCommand extends Command
{
    protected $signature = 'wallet:withdraw {user_id} {wallet_id} {currency_id} {amount}';
    protected $description = 'Withdraw amount from a user wallet';

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        parent::__construct();
        $this->transactionService = $transactionService;
    }

    public function handle()
    {
        $user_id = $this->argument('user_id');
        $wallet_id = $this->argument('wallet_id');
        $currency_id = $this->argument('currency_id');
        $amount = $this->argument('amount');

        $this->transactionService->createTransaction($user_id, $wallet_id, $currency_id, $amount, 'withdrawal');
        $this->info('Amount withdrawn successfully.');
    }
}
