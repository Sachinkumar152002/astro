<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportReplies extends Model
{
    use HasFactory;
    protected $table = 'support_replies';

    public function getReplyPersonAttribute() {
        if($this->reply_type=='admin') {
          return \App\Admin::find($this->reply_by);
        }elseif($this->reply_type=='astrologer') {
          return \App\Model\Astrologer::find($this->reply_by);
        }elseif($this->reply_type=='user') {
          return \App\User::find($this->reply_by);
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
