<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\{User};
use App\Model\{Astrologer, Notification};
use App\Http\Controllers\{PushNotificationController};

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     private $notification = null;
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      $userId = 0;
      $astrologerId = 0;
      $for = -1;
      $users = [];
      $astrologers = [];
      $notification = $this->notification;

      if($notification){
        $userId = $notification->user_id;
        $astrologerId = $notification->astrologer_id;
        $for = $notification->for;
      }

      if($for == 0){
        // for both users and astrologers;
        $users = User::where("status", 1)->where("fcm_token", "!=", "")->get();
        $astrologers = Astrologer::where("status", 1)->where("is_verified", 1)->where("fcm_token", "!=", "")->get();
      }elseif($for == 1){
        // for only users;
        if($userId){
          $users = User::where("id", $userId)->get();
        }else{
          $users = User::where("status", 1)->where("fcm_token", "!=", "")->get();
        }
      }elseif($for == 2){
        // for only astrologers;
        if($astrologerId){
          $astrologers = Astrologer::where("id", $astrologerId)->get();
        }else{
          $astrologers = Astrologer::where("status", 1)->where("is_verified", 1)->where("fcm_token", "!=", "")->get();
        }
      }

      if(count($users) > 0){
        $users->map(function($item, $index) use($notification) {
          $fcmToken = $item->fcm_token;
          // user push notification
          $title = "New";
          $body = $notification->message;
          $dataArray = [
            'type' => "home"
          ];
          $pushNotificationController = new PushNotificationController();
          $pushNotificationController->sendNotification($fcmToken, $title, $body, $dataArray);
        });
      }

      if(count($astrologers) > 0){
        $astrologers->map(function($item, $index) use ($notification) {
          $fcmToken = $item->fcm_token;
          // user push notification
          $title = "New";
          $body = $notification->message;
          $dataArray = [
            'type' => "dashboard"
          ];
          $pushNotificationController = new PushNotificationController();
          $pushNotificationController->sendNotification($fcmToken, $title, $body, $dataArray);
        });
      }

    }
}
