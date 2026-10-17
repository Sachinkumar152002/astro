<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AstrologerReview extends Model
{
    public function user(){
      return $this->hasone('App\User','id','user_id');
    }
    public function astrologer(){
      return $this->hasone('App\Model\Astrologer','id','astrologer_id');
    }

    public function astrologerReview(){
      return $this->hasMany('App\Model\Astrologer','id','astrologer_id');
    }

    public function astrologerInstructor(){
      return $this->hasone('App\Model\Astrologer','id','astrologer_id');
    }
}
