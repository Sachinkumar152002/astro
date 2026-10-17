<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AppointmentAmountJob;
use App\Model\{AiAstrologerAppointment, WalletTransition};
use App\{User};

class AiAppointmentAmountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aiappointment:amount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate ai appointment charges';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $appointments = AiAstrologerAppointment::where('paid', 0)
      ->where('end_at', '<', date('Y-m-d H:i:s', strtotime('now-60seconds')))
      ->get();

      $appointments->map(function($item){
        $startAt=strtotime($item->start_at);
        $endAt=strtotime($item->end_at);
        $totalSeconds=$endAt-$startAt;
        $totalMinutes=ceil($totalSeconds/60);
        $totalCharges=$item->per_min_charge*$totalMinutes;
        $item->charges=$totalCharges;
        $item->paid=1;
        $item->remark = "End Automatically";

        if($item->free == 1){
          $freeAppointmentPrice = $per_min_charge*5;

          if($freeAppointmentPrice < $totalCharges){
            $item->discount =  $freeAppointmentPrice;
          }else{
            $item->discount =  $totalCharges;
          }

          $totalCharges -= $freeAppointmentPrice;

          if($totalCharges < 0){
            $totalCharges = 0;
          }
        }

        $item->save();

        $user = User::find($item->user_id);
        // debit consultation amount from wallet
        $user->wallet -= $totalCharges;
        $user->save();

        // user wallet transition
        $walletTransition = new WalletTransition();
        $walletTransition->user_id = $user->id;
        $walletTransition->user_type = "user";
        $walletTransition->table_id = $item->id;
        $walletTransition->table_name = "ai_astrologer_appointments";
        $walletTransition->action = 0; // debit
        $walletTransition->status = 1; // success
        $walletTransition->remark = "Ai Consultation Completed";
        $walletTransition->invoice = 0;
        $walletTransition->amount = $totalCharges;
        $walletTransition->save();
      });
    }
}
