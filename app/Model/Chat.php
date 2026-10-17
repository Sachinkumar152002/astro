<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
  public function astrologer()
  {
      return $this->hasOne('App\Model\Astrologer', 'id', 'astrologer_id');
  }

  public function user()
  {
      return $this->hasOne('App\User', 'id', 'user_id');
  }

  public function getchatlog()
  {
      return $this->hasMany('App\Model\ChatLog', 'chat_id', 'id');
  }
}
