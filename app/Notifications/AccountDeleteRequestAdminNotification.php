<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeleteRequestAdminNotification extends Notification
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
                ->subject($this->user->type.' Account Deletion Request – '.$this->user->display_name)
                ->greeting('Dear Admin,')
                ->line('We have received a request from '.$this->user->display_name.' to delete their account associated with  '.config('app.name').'. Below are the details of the request:')
                ->line($this->user->type.' Name: '.$this->user->display_name)
                ->line('Email Address: '.$this->user->email)
                ->line('UUID: '.$this->user->uuid)
                ->line('Request Date: '.$this->user->delete_request_on)
                ->line('Please proceed with the necessary steps to process this request in compliance with our data protection policies. If additional verification is required, please reach out to the customer before finalizing the deletion')
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
