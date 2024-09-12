<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class CryptocurrencyBlockchain extends Model
{
    use HasFactory;

    protected $fillable = [
        'cryptocurrency_id', 'blockchain', 'network',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'cryptocurrency_id', 'uuid');
    }
}
