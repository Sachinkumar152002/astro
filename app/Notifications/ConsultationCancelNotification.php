<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsultationCancelNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
   public $data;
   public $from;
   public $to;
   public $status;
   public $message;
    public function __construct($data, $from, $to, $status, $message)
    {
        $this->data = $data;
        $this->from = $from;
        $this->to = $to;
        $this->status = $status;
        $this->message = $message;
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
      $msg = $this->message;
      if($this->from == 'admin'){
        if($this->to == 'user'){
          if($this->status=='accept'){
            return (new MailMessage)
              ->subject(config('app.name').' - Consultation Cancel Request Approved')
              ->view(themeView().'emails.consultation.admin.cancel.accept.user', compact('data', 'msg'));
          }elseif($this->status=='reject'){
            return (new MailMessage)
              ->subject(config('app.name').' - Consultation Cancel Request Disapproved')
              ->view(themeView().'emails.consultation.admin.cancel.reject.user', compact('data', 'msg'));
          }
        }elseif($this->to == 'astrologer'){
          if($this->status=='accept'){
            return (new MailMessage)
              ->subject(config('app.name').' - Consultation Cancel Request Approved')
              ->view(themeView().'emails.consultation.admin.cancel.accept.astrologer', compact('data', 'msg'));
          }elseif($this->status=='reject'){
            return (new MailMessage)
              ->subject(config('app.name').' - Consultation Cancel Request Disapproved')
              ->view(themeView().'emails.consultation.admin.cancel.reject.astrologer', compact('data', 'msg'));
          }
        }
      }elseif($this->from == 'astrologer'){
        if($this->to == 'admin'){
          return (new MailMessage)
            ->subject(config('app.name').' - Consultation Cancel Request')
            ->view(themeView().'emails.consultation.astrologer.cancel', compact('data', 'msg'));
        }
      }elseif($this->from == 'user'){
        if($this->to == 'admin'){
          return (new MailMessage)
            ->subject(config('app.name').' - Consultation Cancel Request')
            ->view(themeView().'emails.consultation.user.cancel', compact('data', 'msg'));
        }
      }

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
