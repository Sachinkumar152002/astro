<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CallHangUp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $callId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($callId = '')
    {
        $this->callId = $callId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        callhangup($this->callId);
    }
}
