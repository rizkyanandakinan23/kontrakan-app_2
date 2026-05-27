<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
        'image',
    ];

    /*
    |-------------------------
    | RELASI CONVERSATION
    |-------------------------
    */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /*
    |-------------------------
    | RELASI SENDER (USER / ADMIN)
    |-------------------------
    */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}