<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\SendSubscribeNotification;
use Illuminate\Notifications\Notifiable;

class NewsletterSubscriber extends Model
{
    use Notifiable;
    public function sendEmail(){
        $this->notify(new SendSubscribeNotification($this));
    }
}
