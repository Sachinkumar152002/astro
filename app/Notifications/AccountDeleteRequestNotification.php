<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeleteRequestNotification extends Notification
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
          ->subject('Confirmation of Your Account Deletion Request')
          ->greeting('Dear '.$this->user->display_name.',')
                    ->line('We received your request to delete your account associated with  '.config('app.name').'. We understand your decision, and we want to ensure the process is smooth and secure for you.')
                    ->line('Before proceeding, please confirm your request by replying to this email. Once confirmed, your account and all associated data will be permanently deleted in accordance with our privacy policies.')
                    ->line('If there’s anything we can assist you with before finalizing this request, such as resolving any issues or discussing alternatives, please let us know—we’re happy to help.')
                    ->line('Thank you for being a valued '.$this->user->type.'.')
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
