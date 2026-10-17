<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AppointmentAmountJob;
use App\Model\{AstrologerAppointment};

class AppointmentAmountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:amount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate appointment charges';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $appointments = AstrologerAppointment::where('scheduled_for', 0)
      ->where('paid', 0)
      ->whereNotIn('status', ['cancel', 'pending', 'failed'])
      ->whereNotNull('user_end_time')
      ->whereNotNull('astrologer_end_time')
      ->where('scheduled_date', date('Y-m-d'))
      ->where('scheduled_time', '<', date('H:i:00'))
      ->where('max_time', '>', date('H:i:00'))
      ->get();
      $appointments->map(function($item){
        AppointmentAmountJob::dispatch($item);
      });
    }
}
