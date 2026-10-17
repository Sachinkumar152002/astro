<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $phone;
    private $message;
    private $template;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone = '', $message = '', $template = '')
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->template = $template;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sendSms($this->phone, $this->message, $this->template);
    }
}
