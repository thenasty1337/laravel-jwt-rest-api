<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cryptocurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'name', 'symbol', 'usd_rate', 'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function blockchains()
    {
        return $this->hasMany(CryptocurrencyBlockchain::class, 'cryptocurrency_id', 'uuid');
    }
}
