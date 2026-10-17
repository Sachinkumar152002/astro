<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\AstrologerAppointment;
use App\Model\CallHistory;
use App\Jobs\RefundCancelAppointmentAmountJob;

class RefundCancelAppointmentAmountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:refundcancelamount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refund and cancel appointment amount';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      //package:
      //call = 3;
      $this->info("start");

      $appointments = AstrologerAppointment::where('refund', 0)
      ->where('is_paid', 0)
      ->where(function($query){
        $query->whereNull('user_start_time')
        ->orWhereNull('user_end_time')
        ->orWhereNull('astrologer_start_time')
        ->orWhereNull('astrologer_end_time');
      })
      ->where('scheduled_date', date('Y-m-d', strtotime('now-4 minutes')))
      ->where('scheduled_time', '<=', date('H:i:00', strtotime('now-4 minutes')))
      ->get();

      foreach($appointments as $key => $appointment){
        RefundCancelAppointmentAmountJob::dispatch($appointment);
      }

      AstrologerAppointment::whereNotIn('status', ['cancel', 'pending', 'failed'])
      ->where(function($query){
        $query->orWhereNull('user_start_time')
        ->orWhereNull('user_end_time')
        ->orWhereNull('astrologer_start_time')
        ->orWhereNull('astrologer_end_time');
      })
      ->where('scheduled_date', "<=", date('Y-m-d'))
      ->where('max_time', '<', date('H:i:00'))
      ->update(["status" => "cancel"]);

      $this->info("end");
    }
}
