<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PujaBookedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $pujaName;

    public function __construct($pujaName)
    {
        $this->pujaName = $pujaName;
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
     * Save notification to custom notifications table
     */
    public function toMail($notifiable)
    {
      return (new MailMessage)
              ->subject('Booked Puja')
              ->greeting('Dear Astrologer,')
              ->line('You have assigned a ' . $this->pujaName .' please check your account in booked puja tab and set Mahurat.')
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
        ];
    }
}
