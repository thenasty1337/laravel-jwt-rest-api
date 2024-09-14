<?php

use Illuminate\Support\Str;

return [
    'endpoint' => env('SPINSHIELD_API_ENDPOINT'),
    'api_login' => env('SPINSHIELD_API_LOGIN'),
    'api_password' => env('SPINSHIELD_API_PASSWORD'),
    'salt' => env('SPINSHIELD_API_SALT'),
    'home_url' => env('APP_URL', 'https://casino.com'),
    'deposit_url' => env('APP_URL', 'https://casino.com'),
    'currency' => 'USD',
    'freespins_currency' => 'USD',
    'game_language' => 'en',
];
