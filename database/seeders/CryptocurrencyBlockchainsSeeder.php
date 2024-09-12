<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class CryptocurrencyBlockchainsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $blockchains = [
            'bitcoin' => ['mainnet', 'testnet'],
            'ethereum' => ['mainnet', 'sepolia'],
            'tron' => ['mainnet', 'nile'],
            'xrp' => ['mainnet', 'testnet'],
            'litecoin' => ['mainnet', 'testnet'],
            'bitcoin-cash' => ['mainnet', 'testnet'],
            'dash' => ['mainnet', 'testnet'],
            'doge' => ['mainnet', 'testnet'],
            'ethereum-classic' => ['mainnet', 'mordor'],
            'bnb-smart-chain' => ['mainnet', 'testnet'],
            'polygon' => ['mainnet', 'amoy'],
            'arbitrum' => ['mainnet', 'sepolia'],
            'optimism' => ['mainnet', 'sepolia'],
            'base' => ['mainnet', 'sepolia'],
            'avalanche' => ['mainnet', 'sepolia'],
            'solana' => ['mainnet', 'testnet'],
        ];

        foreach ($blockchains as $blockchain => $networks) {
            foreach ($networks as $network) {
                DB::table('cryptocurrency_blockchains')->insert([
                    'blockchain' => $blockchain,
                    'network' => $network,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
