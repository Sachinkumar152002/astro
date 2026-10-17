<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\AstrologerAppointment;
use App\Model\CallHistory;
use App\Jobs\CallInitiateJob;

class CallInitiateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consultation:call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initiate call for schedule for later type appointments.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      //package:
      //call = 3;
      $this->info("Consultation Call started");
      $appointments = AstrologerAppointment::where('package', 3)
      ->where('scheduled_date', date('Y-m-d'))
      ->where('scheduled_time', date('H:i:00'))
      ->where('status', 'booked')
      ->get();
      foreach ($appointments as $key => $appointment) {
        $callHistory = CallHistory::where('appointment_id', $appointment->id)->first();
        if($callHistory){
          CallInitiateJob::dispatch($callHistory->id);
          $this->info("call initiate");
        }
      }
    }
}
