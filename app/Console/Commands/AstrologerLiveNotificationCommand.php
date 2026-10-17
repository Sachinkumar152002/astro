<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\{AstrologerLive};
use App\Jobs\{AstrologerLiveNotificationJob};

class AstrologerLiveNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'astrologer:live-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $astrologerLives=AstrologerLive::where('status', 'scheduled')
        ->where("start_at", ">", date("Y-m-d H:i:s"))
        ->where("start_at", "<", date("Y-m-d H:i:s", strtotime("now+5minutes")))
        ->where("notification_sent", 0)
        ->get();
        if($astrologerLives->count()>0){
          AstrologerLiveNotificationJob::dispatch($astrologerLives);
        }
    }
}
