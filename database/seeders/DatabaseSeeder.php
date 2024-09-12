<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public const DEVELOPER_EMAIL = 'developer@example.com';

    public function run(): void
    {
        $this->call([
            CryptocurrencyBlockchainsSeeder::class,
            CryptocurrenciesSeeder::class,
        ]);

        User::factory()->create([
            'name'  => 'Developer',
            'email' => self::DEVELOPER_EMAIL,
        ]);
    }
}
