<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CoursePurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $lms;
    public function __construct($lms)
    {
        $this->lms = $lms;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      $this->lms->sendCoursePurchaseNotification();
    }
}
