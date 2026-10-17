<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AstrologerVerifiedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    private $user;
   public function __construct($user = '')
   {
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
      $astrologer = $this->user;
      return (new MailMessage)
        ->subject(config('app.name').' - Astrologer Verification Success')
        ->view(themeView().'emails.astrologer_verified', compact('astrologer'));
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
