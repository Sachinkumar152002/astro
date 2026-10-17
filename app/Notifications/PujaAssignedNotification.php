<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PujaAssignedNotification extends Notification
{
    use Queueable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $data = $this->data;

        return (new MailMessage)
            ->subject(config("app.name") . ' - New Puja Assignment')
            ->view(themeView().'emails.puja.astrologer.assigned', compact('data'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'puja_assigned',
            'order_id' => $this->data['order_id'],
            'order_number' => $this->data['order_number'],
            'product_id' => $this->data['product_id'],
            'product_name' => $this->data['product_name'],
            'user_id' => $this->data['user_id'],
            'user_name' => $this->data['user_name'],
        ];
    }
}
