<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;
use App\Models\Cryptocurrency;
use App\Models\CryptocurrencyBlockchain;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'uuid',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });

        static::created(function ($model) {
            // Initialize crypto wallets for the user
            $cryptoCurrencies = Cryptocurrency::where('status', 'active')->get();
            foreach ($cryptoCurrencies as $currency) {
                foreach ($currency->blockchains as $blockchain) {
                    $walletId = 'your_wallet_id'; // Replace with your actual wallet ID
                    $network = $blockchain->network; // Use blockchain network

                    app('App\Services\CryptoApisService')->createDepositAddress($walletId, $blockchain->blockchain, $network, $model->uuid, $currency->uuid);
                }
            }
        });
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'user_id', 'uuid');
    }
}
