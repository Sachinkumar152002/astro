<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\{WalletTransition};
use App\Services\AstrologerReferralRewardService;

class AstrologerAppointmentAmountJob implements ShouldQueue
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
    public function handle()
    {
      $appointment = $this->appointment;
      // $user = $appointment->user;
      $appointment->is_paid = 1;
      $appointment->save();

      // credit consultation amount to astrologer wallet
      $astrologer = $appointment->astrologer;
      $astrologer->wallet += $appointment->astrologer_amount;
      $astrologer->save();

      // astrologer wallet transition
      $walletTransition = new WalletTransition();
      $walletTransition->user_id = $astrologer->id;
      $walletTransition->user_type = "astrologer";
      $walletTransition->table_id = $appointment->id;
      $walletTransition->table_name = "astrologer_appointments";
      $walletTransition->action = 1; // credit
      $walletTransition->status = 1; // success
      $walletTransition->remark = "Consultation Completed";
      $walletTransition->invoice = 0;
      $walletTransition->amount = $appointment->astrologer_amount;
      $walletTransition->save();

      // Distribute referral rewards if astrologer used a referral code
      AstrologerReferralRewardService::distributeRewards(
        $astrologer,
        $appointment->astrologer_amount,
        'Consultation',
        $appointment->id,
        'astrologer_appointments'
      );
    }
}
