<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'user_id', 'currency_id', 'currency', 'deposit_address', 'balance', 'is_default',
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

    public function currency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'currency_id', 'uuid');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id', 'uuid');
    }
}
