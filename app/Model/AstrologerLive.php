<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AstrologerLive extends Model
{
    use HasFactory;

    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'astrologer_id');
    }
    public function astrologerLiveUser(){
      return $this->hasMany('App\Model\AstrologerLiveUser', 'astrologer_live_id', 'id');
    }
}
