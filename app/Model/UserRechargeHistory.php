<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\{RechargeInvoiceMailNotification, RechargeFailedNotification};

class UserRechargeHistory extends Model
{
  use Notifiable;

  public function sendRechargeInvoiceMailNotification($pdfUrl){
      $this->notify(new RechargeInvoiceMailNotification($this, $pdfUrl));
  }
  public function sendRechargeFailedNotification(){
      $this->notify(new RechargeFailedNotification($this));
  }
  public function routeNotificationForMail(){
    return $this->customer_email;
  }
  public function invoice(){
     return $this->hasOne('App\Model\Invoice', 'relation_id', 'id')->where('relation_type', 'recharge');
  }
  public function user(){
    $user_id = $this->user_id;
    $user_type = $this->user_type;
    return $this->belongsTo('App\User', 'user_id', 'id');
  }
}
