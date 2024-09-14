<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'wallet_id',
        'currency_id',
        'amount',
        'transaction_type',
        'status',
        'exchange_rate',
        'deposit_address',
        'deposited_from',
        'game_id',
        'transfer_to',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'uuid');
    }

    public function game()
    {
        return $this->belongsTo(Gamelist::class, 'game_id');
    }

    public function transferTo()
    {
        return $this->belongsTo(User::class, 'transfer_to', 'uuid');
    }
}
