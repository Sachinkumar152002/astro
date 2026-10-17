<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackConsultationNotification extends Notification
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
      $user = $appointment->user;
      $astrologer = $appointment->astrologer;
      return (new MailMessage)
        ->subject(config('app.name').' - Astrologer Feedback')
        ->view(themeView().'emails.consultation.feedback.user', compact('appointment', 'user', 'astrologer'));
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
