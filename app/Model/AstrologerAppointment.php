<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\{ConsultationNotification, ConsultationFailedNotification, AstrologerConsultationNotification, FeedbackConsultationNotification, ReminderConsultationNotification, AppointmentDataNotification};
use App\AstrologerUserConsultacy;
use App\Model\ConsultationRequest;

class AstrologerAppointment extends Model
{
    use Notifiable;
    protected $guarded = [];
    private $user_type = 'astrologer';

    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'astrologer_id');
    }

    public function user(){
      return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function astrologerUserConsultacy(){
      return $this->hasOne('App\AstrologerUserConsultacy', 'astrologer_appointment_id', 'id');
    }

    public function callHistory(){
      return $this->hasOne('App\Model\CallHistory', 'appointment_id', 'id');
    }

    public function chat(){
      return $this->hasOne('App\Model\Chat', 'chat_key', 'id');
    }

    public function chats(){
      return $this->hasMany('App\Model\ChatLog', 'chat_id', 'id');
    }

    public function sendConsultationNotification($to){
      $this->user_type = $to;
      $this->notify(new ConsultationNotification($this, $to));
    }
    public function sendConsultationFailedNotification(){
      $this->user_type = 'user';
      $this->notify(new ConsultationFailedNotification($this));
    }
    public function sendAstrologerConsultationNotification(){
      $this->user_type = 'astrologer';
      $this->notify(new AstrologerConsultationNotification($this));
    }
    public function sendFeedbackConsultationNotification($user_type){
      $this->user_type = $user_type;
      $this->notify(new FeedbackConsultationNotification($this));
    }
    public function sendAppointmentData($to){
      $this->user_type = $to;
      $this->notify(new AppointmentDataNotification($this));
    }
    public function sendReminderConsultationNotification(){
      $this->user_type = 'astrologer';
      $this->notify(new ReminderConsultationNotification($this, $this->user_type));
      $this->user_type = 'user';
      $this->notify(new ReminderConsultationNotification($this, $this->user_type));
    }
    public function routeNotificationForMail(){
      if($this->user_type == 'astrologer'){
        return $this->astrologer->email;
      }elseif($this->user_type == 'user'){
        return $this->user->email;
      }elseif($this->user_type == 'customer'){
        return $this->email;
      }elseif($this->user_type == 'admin'){
        return config('vrdcms.admin_email');
      }
    }
    public function hasIllegalActivityInChatAppointments(){
      return $this->hasMany('App\Model\ChatLog','chat_id','id');
    }
}
