<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderConsultationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     private $appointment;
     private $user_type;
    public function __construct($appointment, $user_type)
    {
        $this->appointment = $appointment;
        $this->user_type = $user_type;
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
       $subject = config('app.name').' - Consultation Reminder';
       $view = themeView().'emails.consultation_reminder.user';
       if($this->user_type == 'astrologer'){
         $user = $appointment->astrologer;
         $subject = config('app.name').' - Consultation Reminder';
         $view = themeView().'emails.consultation_reminder.astrologer';
       }
       return (new MailMessage)
         ->subject($subject)
         ->view($view , compact('user', 'appointment'));
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
