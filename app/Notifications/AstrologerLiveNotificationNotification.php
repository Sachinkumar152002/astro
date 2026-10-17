<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AstrologerLiveNotificationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     public $astrologerLive;
     public $astrologer;
     public $to; // astrologer
    public function __construct($astrologerLive, $astrologer, $to)
    {
      $this->astrologerLive=$astrologerLive;
      $this->astrologer=$astrologer;
      $this->to=$to;
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
      $astrologerLive=$this->astrologerLive;
      $astrologer=$this->astrologer;
      $to = $this->to;
      $view="";
      if($to=='astrologer'){
        $subject = "Reminder: Live Stream Starting in 5 Minutes";
        $view = themeView()."emails.astrologerlive.reminder.astrologer";
      }
      return (new MailMessage)
        ->subject($subject)
        ->view($view , compact('astrologerLive', 'astrologer'));
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
