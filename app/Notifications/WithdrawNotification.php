<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
     private $data;
     private $user;
     private $astrologer;
     private $to;
     private $status;
    public function __construct($data, $user, $astrologer, $to, $status){
      $this->data = $data;
      $this->user = $user;
      $this->astrologer = $astrologer;
      $this->to = $to;
      $this->status = $status;
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
      $data = $this->data;
      $user = $this->user;
      $astrologer = $this->astrologer;
      $to = $this->to;
      $status = $this->status;
      $subject = '';
      $view = '';
      if($to=='user'){
        if($status == 'pending'){
          $subject = config('app.name').' - Withdrawal Request Initiated';
          $view = themeView().'emails.withdraw.pending.user';
        }elseif($status == 'done'){
          $subject = config('app.name').' - Your Case Submitted';
          $view = themeView().'emails.withdraw.done.user';
        }elseif($status == 'cancel'){
          $subject = config('app.name').' - Your Case Submitted';
          $view = themeView().'emails.withdraw.cancel.user';
        }
      }elseif($to=='astrologer'){
        if($status == 'pending'){
          $subject = config('app.name').' - Withdrawal Request Initiated – ₹'.$data->amount.' by '.$astrologer->display_name;
          $view = themeView().'emails.withdraw.pending.astrologer';
        }elseif($status == 'done'){
          $subject = config('app.name').' - Your withdrawal request for ₹'.$data->amount.' has been Approved.';
          $view = themeView().'emails.withdraw.done.astrologer';
        }elseif($status == 'cancel'){
          $subject = config('app.name').' - Your withdrawal request for ₹'.$data->amount.' has been Declined.';
          $view = themeView().'emails.withdraw.cancel.astrologer';
        }
      }elseif($to=='admin'){
        if($status == 'pending'){
          $subject = config('app.name').' - New Withdrawal Request by '.$astrologer->display_name.' – ₹'.$data->amount;
          $view = themeView().'emails.withdraw.pending.admin';
        }elseif($status == 'done'){
          $subject = config('app.name').' - Withdrawal Request Approved – '.$astrologer->display_name.', ₹'.$data->amount;
          $view = themeView().'emails.withdraw.done.admin';
        }elseif($status == 'cancel'){
          $subject = config('app.name').' - Withdrawal Request Declined – '.$astrologer->display_name.', ₹'.$data->amount;
          $view = themeView().'emails.withdraw.cancel.admin';
        }
      }
      return (new MailMessage)
        ->subject($subject)
        ->view($view , compact('subject', 'data', 'user', 'astrologer'));
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
