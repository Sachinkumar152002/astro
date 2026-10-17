<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\{FeedbackConsultationJob, EnquiryJob};
use App\Model\{WalletTransition};
use App\Services\CustomerReferralRewardService;

class AppointmentAmountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $appointment;
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
      $currentTime = time();
      $userEndTime = $appointment->user_end_time ? strtotime($appointment->scheduled_date.' '.$appointment->user_end_time) : $currentTime;
      $userDifference = $currentTime - $userEndTime;
      $astrologerEndTime = $appointment->astrologer_end_time ? strtotime($appointment->scheduled_date.' '.$appointment->astrologer_end_time) : $currentTime;
      $astrologerDifference = $currentTime - $astrologerEndTime;
      if($userDifference > 30 || $astrologerDifference > 30){
        $userStartTime = strtotime($appointment->scheduled_date.' '.$appointment->user_start_time);
        $astrologerStartTime = strtotime($appointment->scheduled_date.' '.$appointment->astrologer_start_time);
        $totalMinutes = $userEndTime - $userStartTime;
        if($astrologerStartTime > $userStartTime){
          $totalMinutes = $userEndTime - $astrologerStartTime;
        }
        $totalMinutes = ceil($totalMinutes/60);
        $user = $appointment->user;
        $perMinutePrice = $appointment->actualprice;
        $totalPrice = $perMinutePrice*$totalMinutes;
        $totalPrice = $totalPrice - $appointment->total_price;

        $appointment->total_price = $totalPrice;
        $freeAppointment = $appointment->free;
        $appointment->astrologer_amount = (abs($totalPrice/100)*$appointment->astrologer_amount_ratio);

        if($freeAppointment == 1){
          $freeAppointmentPrice = $perMinutePrice*5;

          if($freeAppointmentPrice < $totalPrice){
            $appointment->discount =  $freeAppointmentPrice;
          }else{
            $appointment->discount =  $totalPrice;
          }

          $appointment->astrologer_amount -= $freeAppointmentPrice;

          if($appointment->astrologer_amount < 0){
            $appointment->astrologer_amount = 0;
          }

          $totalPrice -= $freeAppointmentPrice;

          if($totalPrice < 0){
            $totalPrice = 0;
          }
        }

        $appointment->paid = 1;
        $appointment->comments = "Appointment amount charged automatically.";

        $appointment->scheduled_end_time = date('H:i', $userEndTime);
        $appointment->max_time = date('H:i', $userEndTime+300);

        // if($appointment->lead_mail_sent == 0){
        //   $user = $appointment->user;
        //   $astrologer = $appointment->astrologer;
        //   EnquiryJob::dispatch($appointment, 'send_lead_confirmation', $user->name, $user->email, 'user', $user, $astrologer);
        //   $appointment->lead_mail_sent = 1;
        // }

        // save all the changes of appointment
        $appointment->save();

        // debit consultation amount from user wallet
        $user->wallet -= $totalPrice;
        // save all the changes of user
        $user->save();

        // user wallet transition
        $walletTransition = new WalletTransition();
        $walletTransition->user_id = $user->id;
        $walletTransition->user_type = "user";
        $walletTransition->table_id = $appointment->id;
        $walletTransition->table_name = "astrologer_appointments";
        $walletTransition->action = 0; // debit
        $walletTransition->status = 1; // success
        $walletTransition->remark = "Consultation Completed";
        $walletTransition->invoice = 0;
        $walletTransition->amount = $totalPrice;
        $walletTransition->save();

        // Refresh user to get latest used_referral_code from database
        $user->refresh();

        // Log consultation details for debugging
        \Log::info('AppointmentAmountJob: Processing referral rewards for consultation', [
          'appointment_id' => $appointment->id,
          'user_id' => $user->id,
          'user_name' => $user->name ?? 'NULL',
          'user_email' => $user->email ?? 'NULL',
          'user_referral_code' => $user->referral_code ?? 'NULL',
          'user_used_referral_code' => $user->used_referral_code ?? 'NULL',
          'service_amount' => $totalPrice,
          'service_type' => 'Consultation',
          'astrologer_id' => $appointment->astrologer_id ?? 'NULL'
        ]);

        // Distribute referral rewards if user used a referral code
        $rewardDistributed = CustomerReferralRewardService::distributeRewards(
          $user,
          $totalPrice,
          'Consultation',
          $appointment->id,
          'astrologer_appointments'
        );

        \Log::info('AppointmentAmountJob: Referral reward distribution completed', [
          'appointment_id' => $appointment->id,
          'user_id' => $user->id,
          'user_used_referral_code' => $user->used_referral_code ?? 'NULL',
          'service_amount' => $totalPrice,
          'reward_distributed' => $rewardDistributed ? 'YES' : 'NO'
        ]);

        if($appointment->feedback_mail_sent == 0){
          FeedbackConsultationJob::dispatch($appointment);
          $appointment->feedback_mail_sent = 1;
          $appointment->save();
        }
      }
    }
}
