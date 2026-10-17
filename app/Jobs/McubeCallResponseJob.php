<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\CallHistory;
use App\Jobs\SendSms;
use App\Jobs\CallSuccessMailJob;
use App\Services\AstrologerReferralRewardService;

class McubeCallResponseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cdrRecord;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cdrRecord = [])
    {
        $this->cdrRecord = $cdrRecord;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $callId = $this->cdrRecord['callid'];
        $mycallId = $this->cdrRecord['refid'];
        $callHistory = CallHistory::where('service_call_id', $callId)->where('call_id',$mycallId)->first();
        if($callHistory){
            if($this->cdrRecord['status']=='Call Complete'){
                    $astrologer = $callHistory->astrologer;
                    $percentage = $astrologer->commission ? : 0;
                    $minuteConsumed = round($this->cdrRecord['answeredtime']/60);
                    $billMinutes = $minuteConsumed>$callHistory->max_minute ? $callHistory->max_minute : $minuteConsumed;
                    $callHistory->call_duration_in_sec = $this->cdrRecord['answeredtime'];
                    $callHistory->total_charges = $billMinutes*$callHistory->call_charges;

                    if($astrologer->commission_type){
                        $callHistory->web_commission = $percentage*$billMinutes;
                        $callHistory->commission_amount = $callHistory->total_charges > $percentage*$billMinutes ? $percentage*$billMinutes : $callHistory->total_charges;
                    }else {
                        $callHistory->web_commission = $percentage;
                        $callHistory->commission_amount = ($callHistory->total_charges*$percentage)/100;
                    }

                    $callHistory->astrologer_amount =  $callHistory->total_charges-$callHistory->commission_amount;
                    $callHistory->status_description = $this->cdrRecord['status'];
                    $callHistory->minutes_consumed = $billMinutes;
                    $callHistory->cdr_detail = json_encode($this->cdrRecord);
                    $callHistory->system_call_status = 1;
                    $callHistory->call_end_time = date('Y-m-d H:i:s', strtotime($callHistory->endtime));
                    $callHistory->save();

                    $user = $callHistory->user;
                    $user->wallet = $user->wallet-$callHistory->total_charges;
                    $user->save();

                    // Distribute referral rewards if astrologer used a referral code
                    if($astrologer && $callHistory->astrologer_amount > 0) {
                        AstrologerReferralRewardService::distributeRewards(
                            $astrologer,
                            $callHistory->astrologer_amount,
                            'Call Consultation',
                            $callHistory->id,
                            'call_histories'
                        );
                    }

                    $message = sprintf(config('vrdcms.user_call_success'), $astrologer->display_name,route('astrologers.publicview',$astrologer->slug), $callHistory->minutes_consumed, $user->wallet);
                    SendSms::dispatch($callHistory->from_number, $message, 'user_call_success');
                    $message= sprintf(config('vrdcms.astrologer_call_success'), $user->name);
                    SendSms::dispatch($callHistory->to_number, $message, 'astrologer_call_success');
                    CallSuccessMailJob::dispatch($callHistory);
            }else {
                $callHistory->status_description = $this->cdrRecord['status'];
                $callHistory->save();
            }
        }
    }
}
