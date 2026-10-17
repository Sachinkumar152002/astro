<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\{UserNotificationNotification};
use Illuminate\Support\Facades\Notification;
use App\Model\{UserNotification};
use App\Http\Controllers\{NotificationController,PushNotificationController};

class UserNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $astrologer;
    private $user;
    private $notify_to;
    private $type;
    public function __construct($astrologer,$user,$notify_to,$type)
    {
      $this->astrologer = $astrologer;
      $this->user = $user;
      $this->notify_to = $notify_to;
      $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      $astrologer = $this->astrologer;
      // $user = $this->user;
      $notify_to = $this->notify_to;
      $type = $this->type;
      $userNotifications = UserNotification::where('astrologer_id', $astrologer->id)
      ->where('notify_to', $notify_to)
      ->where('type', $type)
      ->where('is_sent', 0)
      ->get();
      $userNotifications->map(function ($item) use($astrologer){
        // notification
        $notification['user_id'] = $item->user_id;
        $notification['astrologer_id'] = $item->astrologer_id;
        $notification['message'] = $item->description;
        $notification['image_id'] = 0;
        $notification['link'] = route("astrologers.publicview",[$astrologer->type,$astrologer->slug]);
        $notification['app_link'] = "astrologer_detail";
        $notification['for'] = 1;
        $notification['on'] = 0;
        $notification['status'] = 1;
        $notificationController = new NotificationController();
        $notificationController->create($notification);
        $itemUser=$item->user;
        // push notification
        if($itemUser && $itemUser->fcm_token){
          $title=str_replace(["-"],["_"],$item->type);
          $body=$notification['message'];
          $dataArray = [
            'type' => $notification['app_link']
          ];
          $pushNotificationController = new PushNotificationController();
          $pushNotificationController->sendNotification($itemUser->fcm_token, $title, $body, $dataArray);
        }
        // email notification
        $item->sendNotification('user');
        $item->is_sent = 1;
        $item->sent_at = date('Y-m-d H:i:s');
        $item->save();
      });
    }
}
