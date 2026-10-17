<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\CommissionTransaction;
use App\Services\CommissionService;

class DelayedCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $commissionTransaction;

    public function __construct(CommissionTransaction $commissionTransaction)
    {
        $this->commissionTransaction = $commissionTransaction;
    }

    public function handle()
    {
        // Reload transaction to ensure we have latest data
        $transaction = CommissionTransaction::find($this->commissionTransaction->id);

        if($transaction && $transaction->status == 'pending') {
            CommissionService::creditCommission($transaction);
        }
    }
}
