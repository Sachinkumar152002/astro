<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\{UserNotificationNotification};

class UserNotification extends Model
{
    use HasFactory, Notifiable;

    protected $notify_to;

    public function user()
    {
    return $this->hasOne('App\User','id', 'user_id');
    }

    public function astrologer()
    {
    return $this->hasOne('App\Model\Astrologer','id', 'astrologer_id');
    }

    public function sendNotification($notify_to){
      $this->notify_to = $notify_to;
      $this->notify(new UserNotificationNotification($this));
    }

    public function routeNotificationForMail(){
      if($this->notify_to == 'user'){
        return $this->user_email;
      }else{
        return $this->astrologer_email;
      }
    }
}
