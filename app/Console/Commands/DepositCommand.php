<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TransactionService;

class DepositCommand extends Command
{
    protected $signature = 'wallet:deposit {user_id} {wallet_id} {currency_id} {amount}';
    protected $description = 'Deposit amount to a user wallet';

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

        $this->transactionService->createTransaction($user_id, $wallet_id, $currency_id, $amount, 'deposit');
        $this->info('Amount deposited successfully.');
    }
}
