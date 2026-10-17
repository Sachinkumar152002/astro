<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeleteRequestConfirmationNotification extends Notification
{
    use Queueable;

    private $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user)
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
        return (new MailMessage)
          ->subject('Account Deletion Confirmation – '.config("app.name"))
          ->greeting('Dear '.$this->user->display_name.',')
                    ->line('We wanted to confirm that your request to delete your account has been successfully processed. As of '.$this->user->deleted_at.', your account and all associated data have been permanently removed from our system in accordance with our privacy policies.')
                    ->line('If you have any questions or need further assistance, please feel free to reach out. We appreciate the time you’ve spent with us, and should you ever wish to return, we’ll be happy to welcome you back.')
                    ->line('Thank you for being a part of '.config('app.name').', and we wish you all the best!')
                    ;
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
