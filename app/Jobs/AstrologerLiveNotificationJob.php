<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\{AstrologerLiveNotificationNotification};
use App\Http\Controllers\{PushNotificationController, NotificationController};

class AstrologerLiveNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     public $astrologerLives;
    public function __construct($astrologerLives)
    {
      $this->astrologerLives=$astrologerLives;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      $astrologerLives=$this->astrologerLives;
      $astrologerLives->map(function($item){
        $astrologer=$item->astrologer;
        if($astrologer){
          $start_at=date('d/m/Y h:i a', strttotime($item->start_at));
          $message="This is a reminder about your live stream scheduled {$start_at}."
          // normal notification
          $notification['user_id'] = 0;
          $notification['astrologer_id'] = $astrologer->id;
          $notification['message'] = $message;
          $notification['image_id'] = 0;
          $notification['link'] = route('astrologer.live.index');
          $notification['app_link'] = "astrologer_live_scheduled_list";
          $notification['for'] = 2;
          $notification['on'] = 0;
          $notification['status'] = 1;
          $notificationController = new NotificationController();
          $notificationController->create($notification);

          if($fcm_token=$astrologer->fcm_token){
            // push notification
            $title="AstrologerLive";
            $body=$notification['message'];
            $dataArray = [
              'type' => $notification['app_link']
            ];
            $pushNotificationController = new PushNotificationController();
            $pushNotificationController->sendNotification($fcm_token, $title, $body, $dataArray);
          }
          if($email=$astrologer->email){
            // mail notification
            Notification::route('mail', [
              $email=>$astrologer->display_name,
            ])->notify(new AstrologerLiveNotificationNotification($item, $astrologer, 'astrologer'));
          }
        }
        $item->notification_sent=1;
        $item->save();
      });
    }
}
