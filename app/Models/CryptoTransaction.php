<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'wallet_id', 'currency', 'amount', 'transaction_hash',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'uuid');
    }
}
