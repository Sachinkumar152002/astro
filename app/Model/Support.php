<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;
    public function replies(){
      return $this->hasMany('App\Model\SupportReplies', 'support_id', 'id');
    }
    public function user(){
      return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'user_id');
    }


    public function getReplyPersonAttribute() {
        if($this->user_type=='admin') {
          return \App\Admin::find($this->user_id);
        }elseif($this->user_type=='astrologer') {
          return \App\Model\Astrologer::find($this->user_id);
        }elseif($this->user_type=='user') {
          return \App\User::find($this->user_id);
        }
        return null;
    }

    public function getNameAttribute() {
      if($person = $this->reply_person) {
        return $person->name;
      }
        return 'na';
    }

}
