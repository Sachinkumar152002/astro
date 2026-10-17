<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportNotification extends Notification
{
    use Queueable;
    private $user;
    private $user_type;
    private $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct($user = '', $user_type='', $ticket = '')
    {
        $this->user = $user;
        $this->user_type = $user_type;
        $this->ticket = $ticket;
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
      $user = $this->user;
      $user_type = $this->user_type;
      $ticket = $this->ticket;
        return (new MailMessage)
          ->subject(config('app.name').' - Support Ticket Raised')
          ->view(themeView().'emails.support_to_user', compact('user', 'user_type', 'ticket'));
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
