<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
   public $order;
   public $orderRequest;
   public $consignment;
   public $request_type;//cancel, return
   public $status;//0->request, 1->accept, 2->reject
    public function __construct($order, $orderRequest, $consignment, $request_type, $status)
    {
        $this->order = $order;
        $this->orderRequest = $orderRequest;
        $this->consignment = $consignment;
        $this->request_type = $request_type;
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
       $order = $this->order;
       $orderRequest = $this->orderRequest;
       $consignment = $this->consignment;
       $request_type = $this->request_type;
       $status = $this->status;
       if($request_type=='cancel'){
         if($status==0){//request
           return (new MailMessage)
             ->subject(config('app.name').' - Order Cancel Request')
             ->view(themeView().'emails.order.admin.cancel', compact('order', 'orderRequest', 'consignment'));
         }elseif($status==1){//accept
           return (new MailMessage)
             ->subject(config('app.name').' - Order Cancel Request Approved')
             ->view(themeView().'emails.order.user.cancel.accept', compact('order', 'orderRequest', 'consignment'));
         }elseif($status==2){//reject
           return (new MailMessage)
             ->subject(config('app.name').' - Order Cancel Request Disapproved')
             ->view(themeView().'emails.order.user.cancel.reject', compact('order', 'orderRequest', 'consignment'));
         }
       }elseif($request_type=='return'){
         if($status==0){//request
           return (new MailMessage)
             ->subject(config('app.name').' - Order Return Request')
             ->view(themeView().'emails.order.admin.return', compact('order', 'orderRequest', 'consignment'));
         }elseif($status==1){//accept
           return (new MailMessage)
             ->subject(config('app.name').' - Order Return Request Approved')
             ->view(themeView().'emails.order.user.return.accept', compact('order', 'orderRequest', 'consignment'));
         }elseif($status==2){//reject
           return (new MailMessage)
             ->subject(config('app.name').' - Order Return Request Disapproved')
             ->view(themeView().'emails.order.user.return.reject', compact('order', 'orderRequest', 'consignment'));
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
