<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AstrologerConsultationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     private $appointment;
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
      $astrologer = $appointment->astrologer;
      $user = $appointment->user;
        return (new MailMessage)
          ->subject(config('app.name').' - Consultation Appointment Confirmation')
          ->view(themeView().'emails.consultation.astrologer', compact('appointment', 'astrologer', 'user'));
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
