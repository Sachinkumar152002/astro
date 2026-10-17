<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     private $appointment;
     private $to;
    public function __construct($appointment, $to)
    {
        $this->appointment = $appointment;
        $this->to = $to;
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
      $to = $this->to;
      $subject = '';
      $view = '';
      if(!$appointment->package){
        $appointment->name=$user->name;
        $appointment->package=2;
        $appointment->scheduled_date=date('Y-m-d', strtotime($appointment->start_at));
        $appointment->scheduled_time=date('H:i:00', strtotime($appointment->start_at));
      }
      if($to=='user'){
        $subject = config('app.name').' - Your Consultation is Confirmed!';
        $view = themeView().'emails.consultation.user';
      }elseif($to=='admin'){
        $subject = config('app.name').' - New Consultation Booked';
        $view = themeView().'emails.consultation.admin';
      }
      return (new MailMessage)
        ->subject($subject)
        ->view($view , compact('appointment', 'user', 'astrologer'));
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
