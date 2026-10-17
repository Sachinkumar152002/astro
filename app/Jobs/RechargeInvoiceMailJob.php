<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class RechargeInvoiceMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userRechargeHistory;
    private $pdfUrl;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($item='', $pdfUrl='')
    {
        $this->userRechargeHistory = $item;
        $this->pdfUrl = $pdfUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $this->userRechargeHistory->sendRechargeInvoiceMailNotification($this->pdfUrl);
    }
}
