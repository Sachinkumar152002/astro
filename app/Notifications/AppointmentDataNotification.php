<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentDataNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     public $appointment;
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
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
      $appointment = $this->appointment;
        return (new MailMessage)
                    ->line(config('app.name').' - Consultation Appointment Data')
                    ->line('Name: '.$appointment->name);
                    ->line('Gender: '.$appointment->gender);
                    ->line('Date & Time of Birth: '.date('d-m-Y h:i a', strtotime($appointment->dateofbirth.' '.$appointment->birthtime)));
                    ->line('Birth Place: '.$appointment->birthplace);
                    ->action('Document', asset($appointment->document))
                    ->line('Thank you!');
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
