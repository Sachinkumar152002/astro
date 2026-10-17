<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    protected $fillable = ['chat_id', 'user_id', 'astrologer_id', 'message', 'message_by'];
}
