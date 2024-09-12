<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Message extends Model
{
    use HasFactory;
    public $table = 'messages';
    protected $fillable = ['id', 'user_id', 'text', 'deleted_by', 'deleted_at', 'created_at', 'updated_at'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTimeAttribute(): string {
        return date(
            "d M Y, H:i:s",
            strtotime($this->attributes['created_at'])
        );
    }

    public function getDeletedTimeAttribute(): string {
        return date(
            "d M Y, H:i:s",
            strtotime($this->attributes['deleted_at'])
        );
    }


}
