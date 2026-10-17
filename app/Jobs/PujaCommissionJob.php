<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\{WalletTransition, PujaAstrologerAssignment};
use App\Services\AstrologerReferralRewardService;

class PujaCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $assignment;

    public function __construct($assignment)
    {
       $this->assignment = $assignment;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      $assignment = $this->assignment;

      // Reload assignment with relationships
      $assignment = PujaAstrologerAssignment::with(['product', 'astrologer', 'orderItem'])
          ->find($assignment->id);

      if(!$assignment || $assignment->commission_paid) {
        return; // Already processed or assignment not found
      }

      // Check if puja is cancelled
      if(in_array($assignment->status, ['cancelled', 'cancel'])) {
        return; // Don't process commission for cancelled pujas
      }

      // Get product and order item
      $product = $assignment->product;
      $orderItem = $assignment->orderItem;

      if(!$product || !$orderItem) {
        return; // Missing required data
      }

      // Get puja price from order item (total_price is the total amount for this order item)
      $pujaPrice = $orderItem->total_price ?? $orderItem->price ?? 0;

      // Get commission percentage from product
      $commissionPercentage = $product->astrologer_commission_percentage ?? 0;

      if($commissionPercentage <= 0 || $pujaPrice <= 0) {
        return; // No commission configured or invalid price
      }

      // Calculate commission amount
      $commissionAmount = ($pujaPrice * $commissionPercentage) / 100;

      // Update assignment
      $assignment->commission_paid = 1;
      $assignment->commission_amount = $commissionAmount;
      $assignment->save();

      // Credit commission to astrologer wallet
      $astrologer = $assignment->astrologer;
      if($astrologer) {
        $astrologer->wallet += $commissionAmount;
        $astrologer->save();

        // Create wallet transition record
        $walletTransition = new WalletTransition();
        $walletTransition->user_id = $astrologer->id;
        $walletTransition->user_type = "astrologer";
        $walletTransition->table_id = $assignment->id;
        $walletTransition->table_name = "puja_astrologer_assignments";
        $walletTransition->action = 1; // credit
        $walletTransition->status = 1; // success
        $walletTransition->remark = "Puja Commission - " . ($product->title ?? 'Puja');
        $walletTransition->invoice = 0;
        $walletTransition->amount = $commissionAmount;
        $walletTransition->save();

        // Distribute referral rewards if astrologer used a referral code
        AstrologerReferralRewardService::distributeRewards(
          $astrologer,
          $commissionAmount,
          'Puja',
          $assignment->id,
          'puja_astrologer_assignments'
        );
      }
    }
}
