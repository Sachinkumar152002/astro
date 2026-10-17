<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
class CallSuccessMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $callHistory;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($callHistory)
    {
        $this->callHistory = $callHistory;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $callHistory = $this->callHistory;
        Mail::send(themeView().'emails.callsuccess', compact('callHistory'), function($message) use($callHistory){
            $message->to($callHistory->user->email, $callHistory->user->name)->subject('IIAG Call Success #:'.$callHistory->call_id);
        });

        Mail::send(themeView().'emails.callsuccessastrologer', compact('callHistory'), function($message) use($callHistory){
            $message->to($callHistory->astrologer->email, $callHistory->astrologer->display_name)->subject('IIAG Call Success #:'.$callHistory->call_id);
        });
    }
}
