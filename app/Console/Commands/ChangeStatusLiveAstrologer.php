<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\{AstrologerLive};

class ChangeStatusLiveAstrologer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'astrologer:change-live-status-leaved';

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
      AstrologerLive::where('status', 'started')
      ->where("end_at", "<", date("Y-m-d H:i:s", strtotime("now-10seconds")))
      ->update(['status'=>'leaved']);
      AstrologerLive::where('status', 'scheduled')
      ->where("end_at", "<", date("Y-m-d H:i:s", strtotime("now")))
      ->update(['status'=>'cancelled']);
    }
}
