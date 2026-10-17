<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Astrologer;
use App\User;
use App\Admin;

class Advertisement extends Model
{
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }


    public function getUserAttribute(){
      $user_id = $this->user_id;
      $user_type = $this->user_type;
      if($user_type=='astrologer'){
        return Astrologer::find($user_id);
      }elseif($user_type=='user'){
        return user::find($user_id);
      }elseif($user_type=='admin'){
        return Admin::find($user_id);
      }
      return Admin::find(1);
    }

}
