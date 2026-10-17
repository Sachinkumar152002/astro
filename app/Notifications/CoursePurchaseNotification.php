<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CoursePurchaseNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $lmsCourse;
    public function __construct($lmsCourse)
    {
        $this->lmsCourse = $lmsCourse;
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
      $lms = $this->lmsCourse;
      $course = $this->lmsCourse->course;
        return (new MailMessage)
          ->subject(config('app.name').' - Thank You for Your Purchase! Here Are Your Course Details')
          ->view(themeView().'emails.course_enroll', compact('lms', 'course'));
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
