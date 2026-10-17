<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'user_id');
    }
}
