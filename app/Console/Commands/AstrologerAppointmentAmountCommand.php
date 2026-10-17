<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AstrologerAppointmentAmountJob;
use App\Model\AstrologerAppointment;

class AstrologerAppointmentAmountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:astrologerAmount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'astrologer appointment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $appointments = AstrologerAppointment::whereNotIn('status', ['booked', 'cancel', 'pending', 'failed'])
      ->where('is_paid', 0)
      ->where('paid', 1)
      ->where('scheduled_date', date('Y-m-d'))
      ->where('max_time', '<=', date('H:i:00'))
      ->get();
      $appointments->map(function($item){
        AstrologerAppointmentAmountJob::dispatch($item);
      });
    }
}
