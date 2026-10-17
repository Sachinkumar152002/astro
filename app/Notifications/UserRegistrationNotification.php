<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegistrationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $to;
    public $user;
    public function __construct($to, $user)
    {
      $this->to = $to;
      $this->user = $user;
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
      $to = $this->to;
      $user = $this->user;
      $subject = '';
      $view = '';
      if($to=='user'){
        $subject = config('app.name').' - New User Registered';
        $view = themeView().'emails.registration.user.user';
      }elseif($to=='admin'){
        $subject = config('app.name').' - New User Registered';
        $view = themeView().'emails.registration.user.admin';
      }
      return (new MailMessage)
        ->subject($subject)
        ->view($view , compact('user'));
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
