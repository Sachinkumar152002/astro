<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RechargeInvoiceMailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     public $userRechargeHistory;
     public $pdfUrl;
    public function __construct($userRechargeHistory, $pdfUrl='')
    {
        $this->userRechargeHistory = $userRechargeHistory;
        $this->pdfUrl = $pdfUrl;
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
      $m = (new MailMessage)
      ->subject(config('app.name').' - Recharge Successful')
      ->view(themeView().'emails.recharge_success', compact('invoice'));
      if($this->pdfUrl){
        $m->attach($this->pdfUrl);
      }
      return $m;
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
