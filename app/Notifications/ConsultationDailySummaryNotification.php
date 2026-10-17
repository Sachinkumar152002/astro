<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationDailySummaryNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
      private $appointmentsArray;
      private $to;
      private $user;
      private $astrologer;
      public function __construct($appointmentsArray, $to, $user, $astrologer)
      {
          $this->appointmentsArray = $appointmentsArray;
          $this->to = $to;
          $this->user = $user;
          $this->astrologer = $astrologer;
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
      $appointmentsArray = $this->appointmentsArray;
      $to = $this->to;
      $user = $this->user;
      $astrologer = $this->astrologer;
      $subject = '';
      $view = '';
      if($to=='user'){
        $subject = config('app.name').' - Today Consultation Summmary';
        $view = themeView().'emails.consultation.summary.user';
      }elseif($to=='astrologer'){
        $subject = config('app.name').' - Today Consultation Summmary';
        $view = themeView().'emails.consultation.summary.astrologer';
      }
      return (new MailMessage)
        ->subject($subject)
        ->view($view , compact('appointmentsArray', 'user', 'astrologer'));
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
