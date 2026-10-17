<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PujaMediaApprovedNotification extends Notification
{
    use Queueable;

    private $userName;
    private $platformName;

    public function __construct($userName)
    {
        $this->userName = $userName;
        $this->platformName = config('app.name');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(config('app.name') . ' - Puja Photos & Videos Ready')
            ->view(themeView() . 'emails.puja.media_approved', [
                'userName' => $this->userName,
                'platformName' => $this->platformName,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
