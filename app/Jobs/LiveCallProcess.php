<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\CallHistory;
use App\Jobs\CallHangUp;

class LiveCallProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $liveCall;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($liveCall = [])
    {
        $this->liveCall = $liveCall;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->liveCall['state']=='Answered'){
            $source = $this->liveCall['source'];
            $astrologerNumber = substr($source, -10);
            $destination = explode(',',$this->liveCall['destination']);
            if(count($destination)==2){
                $customerNumber = substr($destination[0], -10);
                $CallHistory  = CallHistory::where('from_number', $customerNumber)->where('to_number', $astrologerNumber)->whereNull('service_call_id')->whereNull('user_status')->orderBy('id', 'desc')->first();
                if($CallHistory){
                    CallHangUp::dispatch($this->liveCall['call_id'])->delay(now()->addMinutes($CallHistory->max_minute));
                    $CallHistory->user_status = $this->liveCall['state'];
                    $CallHistory->service_call_id = $this->liveCall['call_id'];
                    $CallHistory->call_start_time = date('Y-m-d H:i:s');
                    $CallHistory->call_hangup_time = date('Y-m-d H:i:s', strtotime('+'.$CallHistory->max_minute.' minutes'));
                    $CallHistory->save();
                }
            }
        }
    }
}
