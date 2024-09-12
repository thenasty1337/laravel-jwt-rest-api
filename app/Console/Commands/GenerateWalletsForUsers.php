<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Cryptocurrency;
use App\Services\CryptoApisService;

class GenerateWalletsForUsers extends Command
{
    protected $signature = 'wallets:generate';
    protected $description = 'Generate wallets for users who do not have them';

    protected $cryptoApisService;

    public function __construct(CryptoApisService $cryptoApisService)
    {
        parent::__construct();
        $this->cryptoApisService = $cryptoApisService;
    }

    public function handle()
    {
        $this->info('Starting wallet generation for users...');

        $users = User::all();
        $activeCryptoCurrencies = Cryptocurrency::where('status', 'active')->get();

        foreach ($users as $user) {
            foreach ($activeCryptoCurrencies as $currency) {
                foreach ($currency->blockchains as $blockchain) {
                    // Check if the user already has a wallet for this blockchain
                    $walletExists = $user->wallets()->where('currency_id', $currency->uuid)->where('currency', $blockchain->blockchain)->exists();

                    if (!$walletExists) {
                        $walletId = '60c9d9921c38030006675ff6';
                        $network = $blockchain->network;

                        $this->cryptoApisService->createDepositAddress($walletId, $blockchain->blockchain, $network, $user->uuid, $currency->uuid);
                        $this->info("Created {$blockchain->blockchain} wallet for user {$user->email}");
                    } else {
                        $this->info("User {$user->email} already has a {$blockchain->blockchain} wallet");
                    }
                }
            }
        }

        $this->info('Wallet generation completed.');
    }
}
