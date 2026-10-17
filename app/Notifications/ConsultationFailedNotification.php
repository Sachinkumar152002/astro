<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationFailedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     private $data;
     private $to;
     private $user;
     private $astrologer;
    public function __construct($data, $to, $user, $astrologer)
    {
        $this->data = $data;
        $this->to = $to;
        $this->user = $user;
        $this->astrologer = $astrologer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
      $data = $this->data;
      $to = $this->to;
      $user = $this->user;
      $astrologer = $this->astrologer;
      $subject = '';
      $view = '';
      if($to=='user'){
        if($data->package == 1){ //video
          $subject = config('app.name').' - You Missed Your Scheduled Video Consultation';
        }elseif($data->package == 2){ //chat
          $subject = config('app.name').' - You Missed Your Scheduled Chat Consultation';
        }elseif($data->package == 3){ //call
          $subject = config('app.name').' - Your Call Consultation Was Cancelled Due to No Response';
        }
        $view = themeView().'emails.consultation.cancel.user';
      }elseif($to=='astrologer'){
        if($data->package == 1){ //video
          $subject = config('app.name').' - cancel!';
        }elseif($data->package == 2){ //chat
          $subject = config('app.name').' - cancel!';
        }elseif($data->package == 3){ //call
          $subject = config('app.name').' - cancel!';
        }
        $view = themeView().'emails.consultation.cancel.astrologer';
      }elseif($to=='admin'){
        if($data->package == 1){ //video
          $subject = config('app.name').' - Video Call Auto-Cancelled – '.$data->name.' Didn’t Join';
        }elseif($data->package == 2){ //chat
          $subject = config('app.name').' - Chat Consultation Auto-Cancelled – '.$data->name.' Did Not Join';
        }elseif($data->package == 3){ //call
          $subject = config('app.name').' - Call Consultation Auto-Cancelled – '.$data->name.' Missed the Call';
        }
        $view = themeView().'emails.consultation.cancel.admin';
      }
      return (new MailMessage)
        ->subject($subject)
        ->view($view , compact('subject', 'data', 'user', 'astrologer'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
