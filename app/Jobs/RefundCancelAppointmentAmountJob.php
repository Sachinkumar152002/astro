<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\WalletTransition;
use App\Jobs\{ConsultationFailedJob};

class RefundCancelAppointmentAmountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $appointment;
    public function __construct($appointment)
    {
      $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      $appointment = $this->appointment;
      $user = $appointment->user;
      $appointment->status = 'cancel';
      $appointment->astrologer_status = 'cancel';
      $appointment->user_status = 'cancel';
      if($appointment->paid){

        $user->wallet += $appointment->total_price;
        $user->save();

        $walletTransition = WalletTransition::where('user_id', $user->id)->where('user_type', 'user')->where('table_id', $appointment->id)->where('table_name', 'astrologer_appointments')->where('action', 1)->where('status', 1)->first();
        if(!$walletTransition){
          $walletTransition = new WalletTransition();
          $walletTransition->user_id = $user->id;
          $walletTransition->user_type = "user";
          $walletTransition->table_id = $appointment->id;
          $walletTransition->table_name = "astrologer_appointments";
          $walletTransition->action = 1; //credit
          $walletTransition->status = 1;
        }
        $walletTransition->amount = $appointment->total_price;
        $walletTransition->save();
      }
      $appointment->refund = 1;
      $appointment->comments = "Automatic Cancelled.";
      $appointment->save();
      $astrologer = $appointment->astrologer;
      ConsultationFailedJob::dispatch($appointment, $user->name, $user->email, 'user', $user, $astrologer);
      ConsultationFailedJob::dispatch($appointment, $astrologer->display_name, $astrologer->email, 'astrologer', $user, $astrologer);
      ConsultationFailedJob::dispatch($appointment, 'Admin', config('vrdcms.admin_email'), 'admin', $user, $astrologer);
    }
}
