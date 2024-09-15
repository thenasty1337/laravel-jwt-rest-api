<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'chat_messages';


    protected $fillable = [
        'user_id',
        'content',
        'is_visible',
        'deleted_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
