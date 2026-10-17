<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RechargeFailedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     public $userRechargeHistory;
    public function __construct($userRechargeHistory)
    {
        $this->userRechargeHistory = $userRechargeHistory;
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
      $invoice = $this->userRechargeHistory;
        return (new MailMessage)
          ->subject(config('app.name').' - Failed Transaction Notification')
          ->view(themeView().'emails.recharge_fail', compact('invoice'));
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
