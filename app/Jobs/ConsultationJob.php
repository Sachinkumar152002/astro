<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConsultationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
     private $appointment;
    public function __construct($appointment = '')
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      $this->appointment->sendConsultationNotification('user');
      $this->appointment->sendConsultationNotification('admin');
    }
}
