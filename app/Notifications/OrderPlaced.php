<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use App\Http\Controllers\{ApiController};

class OrderPlaced extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $order;
    private $to; // user, admin
    private $product_type; // product, pooja, service
    private $pdf_url;
    public function __construct($order, $to, $product_type, $pdf_url)
    {
        $this->order = $order;
        $this->to = $to;
        $this->product_type = $product_type;
        $this->pdf_url = $pdf_url;
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
      // Ensure orderItems.product relationship is loaded
      if($order && !$order->relationLoaded('orderItems')) {
        $order->load('orderItems.product');
      }
      $to = $this->to;
      $product_type = $this->product_type;
      $download_url = "";

      $m = (new MailMessage);

      if($to == 'admin'){
        $m->subject(config("app.name").' - New Order Received');
        $m->view(themeView().'emails.order.admin.success', compact('order'));
      }elseif($to == 'user'){
        if($product_type == "service"){
          $apiController = new ApiController();
          $download_url = $apiController->getResponse($order->id);
        }

        $m->subject(config("app.name").' - New Order Received');
        $m->view(themeView().'emails.order.user.success', compact('order'));
      }

      if($this->pdf_url){
        $m->attach($this->pdf_url);
      }

      if($download_url){
        $m->attach($download_url);
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
