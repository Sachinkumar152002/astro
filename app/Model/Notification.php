<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function user(){
      return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'astrologer_id');
    }
}
