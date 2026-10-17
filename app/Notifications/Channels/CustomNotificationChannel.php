<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Model\Notification as NotificationModel;

class CustomNotificationChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (method_exists($notification, 'toCustom')) {
            $notification->toCustom($notifiable);
        }
    }
}
