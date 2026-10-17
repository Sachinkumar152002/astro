<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PujaCancelledNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $pujaName;
    private $reason;

    public function __construct($pujaName, $reason)
    {
        $this->pujaName = $pujaName;
        $this->reason = $reason;
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
                    ->subject('Puja Cancelled')
                    ->greeting('Dear Astrologer,')
                    ->line($this->pujaName . ' has been cancelled by the user.')
                    ->line('Reason: ' . $this->reason);
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
