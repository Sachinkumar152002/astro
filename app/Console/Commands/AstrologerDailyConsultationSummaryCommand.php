<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ConsultationDailySummaryJob;
use App\Model\AstrologerAppointment;

class AstrologerDailyConsultationSummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:astrologerSummary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Daily Summary of appointments on email to astrologer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $appointments = AstrologerAppointment::where('scheduled_date', date('Y-m-d'))
      ->whereNotIn('status', ['cancel', 'pending', 'failed'])
      ->get();
      $groupedAppointments = $appointments->groupBy('astrologer_id');
      $groupedAppointments->map(function($appointmentsArray){
        if($astrologer = $appointmentsArray[0]->astrologer){
          $user = $appointmentsArray[0]->user;
          ConsultationDailySummaryJob::dispatch($appointmentsArray, $astrologer->email, $astrologer->display_name, 'astrologer', $user, $astrologer);
        }
      });
    }
}
