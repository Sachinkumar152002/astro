<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RechargeFailedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $userRechargeHistory;
    public function __construct($item='')
    {
        $this->userRechargeHistory = $item;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      $this->userRechargeHistory->sendRechargeFailedNotification();
    }
}
