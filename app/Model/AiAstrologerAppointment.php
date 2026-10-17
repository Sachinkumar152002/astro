<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\{ConsultationNotification};

class AiAstrologerAppointment extends Model
{
    use HasFactory;

    private $user_type = 'user';

    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'astrologer_id');
    }

    public function user(){
      return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function chats(){
      return $this->hasMany('App\Model\AiAstrologerAppointmentChat', 'appointment_id', 'id');
    }

    public function sendConsultationNotification($to){
      $this->user_type = $to;
      $this->notify(new ConsultationNotification($this, $to));
    }
}
