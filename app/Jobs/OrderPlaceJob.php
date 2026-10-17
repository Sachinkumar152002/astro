<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\{OrderPlaced, PujaBookedNotification};
use App\Services\{ReferralService, CommissionService};

class OrderPlaceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $mail_credential; // [email => name]
    private $order;
    private $to; // user, admin
    private $product_type; // product, pooja, service
    private $pdf_url;
    public function __construct($mail_credential, $order, $to, $product_type, $pdf_url)
    {
        $this->mail_credential = $mail_credential;
        $this->order = $order;
        $this->to = $to;
        $this->product_type = $product_type;
        $this->pdf_url = $pdf_url;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
      Notification::route('mail', $this->mail_credential)
      ->notify(new OrderPlaced($this->order, $this->to, $this->product_type, $this->pdf_url));

      // Send PujaBookedNotification to astrologers when order is successful (only for user notifications)
      if($this->to == 'user' && $this->order) {
        $order = $this->order;
        // Reload order with relationships to get latest data from database
        $order = \App\Order::with(['orderItems.product', 'user'])->find($order->id);

        if($order && $order->orderItems && $order->user) {
          // Refresh order to get latest status and payment_status
          $order->refresh();

          // Refresh user to get latest used_referral_code from database
          $order->user->refresh();

          // Get order total amount
          $orderTotal = $order->total_price ?? $order->net_total ?? 0;

          // Distribute referral rewards for order (product purchase/puja participation)
          // Only process once per order, so check if order status is Success (case insensitive)
          $orderStatus = strtolower(trim($order->status ?? ''));
          $paymentStatus = strtolower(trim($order->payment_status ?? ''));

          // Log order details for debugging - including all important fields
          \Log::info('OrderPlaceJob: Starting referral reward processing', [
            'order_id' => $order->id,
            'order_number' => $order->order_number ?? 'NULL',
            'order_status' => $order->status ?? 'NULL',
            'order_status_lowercase' => $orderStatus,
            'payment_status' => $order->payment_status ?? 'NULL',
            'payment_status_lowercase' => $paymentStatus,
            'user_id' => $order->user->id ?? 'NULL',
            'user_name' => $order->user->name ?? 'NULL',
            'user_email' => $order->user->email ?? 'NULL',
            'user_referral_code' => $order->user->referral_code ?? 'NULL',
            'user_used_referral_code' => $order->user->used_referral_code ?? 'NULL',
            'order_total_price' => $order->total_price ?? 0,
            'order_net_total' => $order->net_total ?? 0,
            'order_total' => $orderTotal,
            'product_type' => $this->product_type ?? 'NULL',
            'condition_met' => ($orderStatus == 'success' && ($paymentStatus == 'success' || $paymentStatus == 'cod'))
          ]);

          // Check if order is successful (case insensitive)
          // Accept: 'success', 'Success', 'SUCCESS', etc.
          // Accept payment: 'success', 'Success', 'cod', 'COD', 'Cod', etc.
          // This applies to ALL order types: product, puja, service
          if($orderStatus == 'success' &&
             ($paymentStatus == 'success' || $paymentStatus == 'cod')) {

            // Determine service type based on product_type
            $serviceType = 'Product Purchase';
            if($this->product_type == 'pooja' || $this->product_type == 'puja') {
              $serviceType = 'Puja';
            } elseif($this->product_type == 'service') {
              $serviceType = 'Service';
            }

            \Log::info('OrderPlaceJob: Condition met - distributing referral rewards', [
              'order_id' => $order->id,
              'service_type' => $serviceType,
              'service_amount' => $orderTotal,
              'user_id' => $order->user->id,
              'user_used_referral_code' => $order->user->used_referral_code ?? 'NULL'
            ]);

            // Process App Referral Reward (if trigger is first_purchase)
            $appReferralProcessed = ReferralService::processReferralReward(
              $order->user,
              'user',
              $order->id
            );

            // Process Product Commission
            // Ensure user has latest referral code before processing commission
            $order->user->refresh();
            if($order->referral_code && !$order->user->used_referral_code) {
                $order->user->used_referral_code = $order->referral_code;
                $order->user->save();
                \Log::info('OrderPlaceJob: Updated user used_referral_code from order', [
                    'order_id' => $order->id,
                    'user_id' => $order->user->id,
                    'referral_code' => $order->referral_code
                ]);
            }

            $commissionProcessed = CommissionService::processOrderCommission($order);

            \Log::info('OrderPlaceJob: Referral processing completed', [
              'order_id' => $order->id,
              'user_id' => $order->user->id,
              'user_name' => $order->user->name ?? 'NULL',
              'user_referral_code' => $order->user->referral_code ?? 'NULL',
              'user_used_referral_code' => $order->user->used_referral_code ?? 'NULL',
              'service_type' => $serviceType,
              'service_amount' => $orderTotal,
              'order_total' => $orderTotal,
              'app_referral_processed' => $appReferralProcessed ? 'YES' : 'NO',
              'commission_processed' => $commissionProcessed ? 'YES' : 'NO'
            ]);
          } else {
            \Log::warning('OrderPlaceJob: Referral rewards not distributed - condition not met', [
              'order_id' => $order->id,
              'order_status' => $order->status ?? 'NULL',
              'payment_status' => $order->payment_status ?? 'NULL',
              'order_status_lowercase' => $orderStatus,
              'payment_status_lowercase' => $paymentStatus,
              'user_id' => $order->user->id ?? 'NULL',
              'user_used_referral_code' => $order->user->used_referral_code ?? 'NULL',
              'service_amount' => $orderTotal,
              'reason' => 'Order status or payment status does not meet requirements'
            ]);
          }

          foreach($order->orderItems as $orderItem) {
            if($orderItem->type == 'shop' && $orderItem->product) {
              $product = $orderItem->product;
              // Check if this is a one-to-one puja
              if($product->type == 2 && $product->is_one_to_one_puja == 1) {
                // Find the assignment for this order item
                $assignment = \App\Model\PujaAstrologerAssignment::where('order_id', $order->id)
                  ->where('order_item_id', $orderItem->id)
                  ->where('product_id', $product->id)
                  ->with('astrologer')
                  ->first();

                if($assignment && $assignment->astrologer) {
                  $pujaName = $product->title ?? 'Puja';
                  $assignment->astrologer->notify(new PujaBookedNotification($pujaName));
                }
              }
            }
          }
        }
      }
    }
}
