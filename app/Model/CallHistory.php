<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    public function astrologer()
    {
        return $this->hasOne('App\Model\Astrologer', 'id', 'astrologer_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
