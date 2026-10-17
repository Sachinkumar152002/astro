<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PujaMahuratSetNotification extends Notification
{
    use Queueable;

    private $userName;
    private $astrologerName;
    private $pujaName;
    private $mahuratDate;
    private $mahuratTime;
    private $videoCallUrl;

    public function __construct($userName, $astrologerName, $pujaName, $mahuratDate, $mahuratTime, $videoCallUrl = null)
    {
        $this->userName = $userName;
        $this->astrologerName = $astrologerName;
        $this->pujaName = $pujaName;
        $this->mahuratDate = $mahuratDate;
        $this->mahuratTime = $mahuratTime;
        $this->videoCallUrl = $videoCallUrl;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $formattedDate = date('d M Y', strtotime($this->mahuratDate));
        $formattedTime = date('h:i A', strtotime($this->mahuratTime));
        $platformName = config('app.name');

        return (new MailMessage)
            ->subject(config('app.name') . ' - Puja Mahurat Scheduled')
            ->view(themeView() . 'emails.puja.mahurat_set', [
                'userName' => $this->userName,
                'astrologerName' => $this->astrologerName,
                'pujaName' => $this->pujaName,
                'mahuratDate' => $formattedDate,
                'mahuratTime' => $formattedTime,
                'videoCallUrl' => $this->videoCallUrl,
                'platformName' => $platformName,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
