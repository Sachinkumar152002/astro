<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Http\Controllers\ExotelController;
use App\Services\ExotelService;

class CallInitiateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     private $call_history_id;
    public function __construct($call_history_id)
    {
        $this->call_history_id = $call_history_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $exotelService = new ExotelService();
        $exotelController = new ExotelController($exotelService);
        $newRequest = new Request([
          'call_history_id'=>$this->call_history_id,
        ]);
        $exotelController->clickToCall($newRequest);
    }

}
