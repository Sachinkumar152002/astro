<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\PujaMahurat;
use App\Http\Controllers\{NotificationController, PushNotificationController};
use Carbon\Carbon;

class PujaMahuratReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mahurat;
    private $hoursBefore;

    /**
     * Create a new job instance.
     */
    public function __construct(PujaMahurat $mahurat, $hoursBefore)
    {
        $this->mahurat = $mahurat;
        $this->hoursBefore = $hoursBefore;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mahurat = $this->mahurat->fresh(['assignment.product', 'assignment.order.user', 'assignment.astrologer']);

        if(!$mahurat->assignment || !$mahurat->assignment->order || !$mahurat->assignment->order->user) {
            return;
        }

        $user = $mahurat->assignment->order->user;
        $product = $mahurat->assignment->product;
        $assignment = $mahurat->assignment;
        $pujaName = $product ? ($product->title ?? 'Puja') : 'Puja';
        $userName = $user->name ?? 'User';
        $astrologerName = $assignment->astrologer ? trim(($assignment->astrologer->firstname ?? '') . ' ' . ($assignment->astrologer->lastname ?? '')) : 'Astrologer';
        $videoCallUrl = $product && !empty($product->video_call_url) ? $product->video_call_url : null;

        // Format date properly (date is cast to date, so format it as Y-m-d)
        $dateString = $mahurat->date instanceof \Carbon\Carbon
            ? $mahurat->date->format('Y-m-d')
            : (is_string($mahurat->date) ? $mahurat->date : date('Y-m-d', strtotime($mahurat->date)));

        // Format time properly
        $timeString = $mahurat->time;
        if($timeString instanceof \Carbon\Carbon) {
            $timeString = $timeString->format('H:i:s');
        } elseif(is_string($timeString)) {
            // If time is already a string, use it as is (could be H:i or H:i:s)
            $timeString = $timeString;
        } else {
            \Log::error('Invalid time format for mahurat ID: ' . $mahurat->id);
            return;
        }

        // Parse the combined date and time (try with seconds first, then without)
        try {
            if(strlen($timeString) > 5) {
                // Has seconds (H:i:s format)
                $mahuratDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ' ' . $timeString);
            } else {
                // No seconds (H:i format)
                $mahuratDateTime = Carbon::createFromFormat('Y-m-d H:i', $dateString . ' ' . $timeString);
            }
            $formattedDate = $mahuratDateTime->format('d M Y');
            $formattedTime = $mahuratDateTime->format('h:i A');
        } catch (\Exception $e) {
            \Log::error('Error parsing mahurat datetime in job: ' . $e->getMessage() . ' - Date: ' . $dateString . ' Time: ' . $timeString);
            return;
        }

        // Create notification message
        $message = "Your {$pujaName} is scheduled on {$formattedDate} at {$formattedTime}. Kindly join on time.";

        // Create in-app notification
        $notification = [
            'user_id' => $user->id,
            'astrologer_id' => 0,
            'message' => $message,
            'image_id' => 0,
            'link' => route('user.pujas'),
            'app_link' => 'puja_details',
            'for' => 1,
            'on' => 0,
            'status' => 1
        ];

        $notificationController = new NotificationController();
        $notificationController->create($notification);

        // Send push notification
        if($user->fcm_token) {
            $title = "Puja Reminder";
            $body = $message;
            $dataArray = [
                'type' => $notification['app_link']
            ];
            $pushNotificationController = new PushNotificationController();
            $pushNotificationController->sendNotification($user->fcm_token, $title, $body, $dataArray);
        }

        // Send email notification (using the same notification class as mahurat set)
        try {
            $user->notify(new \App\Notifications\PujaMahuratSetNotification($userName, $astrologerName, $pujaName, $mahurat->date, $mahurat->time, $videoCallUrl));
        } catch (\Exception $e) {
            \Log::error('Error sending puja mahurat reminder email: ' . $e->getMessage());
        }
    }
}
