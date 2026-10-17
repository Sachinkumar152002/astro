<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\{Withdraw, Astrologer, Setting};

class AstrolgerAutoPayoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'astrologer:autoPay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'after 15 days auto pay';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      // $minumum_withraw_amount = Setting::where('key', 'minumum_withraw_amount')->first();
      // $minumum_withraw_amount = ($minumum_withraw_amount && $minumum_withraw_amount->value > 0 ? (double) $minumum_withraw_amount->value : 0);
      //
      // $maximum_withraw_amount = Setting::where('key', 'maximum_withraw_amount')->first();
      // $maximum_withraw_amount = ($maximum_withraw_amount && $maximum_withraw_amount->value > 0 ? (double) $maximum_withraw_amount->value : 0);

      $minumum_withraw_amount = 10;

      $hold_withraw_amount = Setting::where('key', 'hold_withraw_amount')->first();
      // $hold_withraw_amount = ($hold_withraw_amount && $hold_withraw_amount->value ? (double) $hold_withraw_amount->value : 0);
      $hold_withraw_percentage = ($hold_withraw_amount && $hold_withraw_amount->value ? (double) $hold_withraw_amount->value : 0);

      $astrologers = Astrologer::where('type', 'Astrologer')
      ->where('status', 1)
      ->where('is_verified', 1)
      ->where('wallet', ">=", $minumum_withraw_amount)
      ->get();
      $astrologers->map(function($item) use ($hold_withraw_percentage){
        $walletAmount = $item->wallet;
        $holdAmount = (($walletAmount/100)*$hold_withraw_percentage);
        $withdrawAmount = $walletAmount - $holdAmount;

        if($withdrawAmount >= 1){
          $withdraw = new Withdraw();
          $withdraw->user_id = $item->id;
          $withdraw->user_type = 'astrologer';
          $withdraw->amount = $withdrawAmount;
          $withdraw->status = 'pending';
          $withdraw->save();

          $item->wallet -= $withdrawAmount;
          $item->save();
        }

      });
    }
}
