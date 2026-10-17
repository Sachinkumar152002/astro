<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SupportUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     private $user;
     private $ticket;
    public function __construct($user, $ticket){
      $this->user = $user;
      $this->ticket = $ticket;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      $this->user->sendSupportNotification($this->ticket);
    }
}
