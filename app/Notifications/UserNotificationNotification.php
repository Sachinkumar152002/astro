<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotificationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $data;
    public function __construct($data)
    {
      $this->data = $data;
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
        $type = $data->type;
        $notify_to = $data->notify_to;
        $user = $data->user;
        $astrologer = $data->astrologer;
        $subject = '';
        $view = '';
        if($type == 'astrologer-online'){
          if($notify_to == 'user'){
            $subject = config('app.name').' - Astrologer Online';
            $view = themeView().'emails.usernotification.astrologeronline.user';
          }
        }elseif($type == 'astrologer-live'){
          if($notify_to == 'user'){
            $subject = config('app.name').' - Astrologer Live';
            $view = themeView().'emails.usernotification.astrologerlive.user';
          }
        }
        return (new MailMessage)
        ->subject($subject)
        ->view($view , compact('data', 'user', 'astrologer'));
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
