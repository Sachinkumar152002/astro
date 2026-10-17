<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ConsultationReminderJob;
use App\Model\AstrologerAppointment;

class ReminderConsultationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:consultation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consultation reminder before 10 minutes one time.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $appointments = AstrologerAppointment::where('status', 'booked')
      ->where('is_reminded', 0)
      ->where('scheduled_date', date('Y-m-d'))
      ->where('scheduled_time', date('H:i:00', strtotime('now+10minutes')))
      ->where('scheduled_for', 1)
      ->get();
      if(count($appointments)){
        foreach ($appointments as $key => $appointment) {
          ConsultationReminderJob::dispatch($appointment);
          $appointment->is_reminded = 1;
          $appointment->reminder_time = date('Y-m-d H:i:00');
          $appointment->save();
          echo 'reminder';
        }
      }
    }
}
