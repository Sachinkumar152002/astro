<?php

namespace App\Services;

use App\Model\{ProductReferralConfiguration, CategoryCommission, CommissionTransaction, CommissionWallet};
use App\{Order, OrderItem};
use App\User;
use App\Model\Astrologer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionService
{
    /**
     * Process product commission for an order
     *
     * @param Order $order
     * @return bool
     */
    public static function processOrderCommission($order)
    {
        Log::info('CommissionService: Starting commission processing', [
            'order_id' => $order->id ?? 'NULL',
            'order_number' => $order->order_number ?? 'NULL',
            'order_status' => $order->status ?? 'NULL',
            'payment_status' => $order->payment_status ?? 'NULL'
        ]);

        // Get active product referral configuration
        $config = ProductReferralConfiguration::where('status', 1)
            ->where('enable_product_referral', true)
            ->first();

        if(!$config) {
            Log::info('CommissionService: Product referral is disabled or not configured', [
                'order_id' => $order->id ?? 'NULL'
            ]);
            return false;
        }

        Log::info('CommissionService: Product referral config found', [
            'config_id' => $config->id,
            'enable_product_referral' => $config->enable_product_referral,
            'applicable_user_types' => $config->applicable_user_types ?? []
        ]);

        // Get order user
        $orderUser = $order->user;
        if(!$orderUser) {
            Log::info('CommissionService: Order user not found');
            return false;
        }

        // Check if user type is applicable
        $userType = 'user'; // Default to user
        if(!in_array($userType, $config->applicable_user_types ?? [])) {
            Log::info('CommissionService: User type not applicable');
            return false;
        }

        // Get the referral code used by the order user
        // First check order's referral_code field, then user's used_referral_code
        $usedReferralCode = $order->referral_code ?? $orderUser->used_referral_code ?? null;

        Log::info('CommissionService: Checking referral code', [
            'order_id' => $order->id,
            'order_referral_code' => $order->referral_code ?? 'NULL',
            'user_used_referral_code' => $orderUser->used_referral_code ?? 'NULL',
            'final_referral_code' => $usedReferralCode ?? 'NULL'
        ]);

        if(!$usedReferralCode || trim($usedReferralCode) == '') {
            Log::info('CommissionService: No referral code used', [
                'order_id' => $order->id,
                'user_id' => $orderUser->id
            ]);
            return false;
        }

        // Find the referrer
        $referrer = null;
        $referrerType = null;

        $referringUser = User::where('referral_code', $usedReferralCode)
            ->where('status', 1)
            ->first();

        if($referringUser) {
            $referrer = $referringUser;
            $referrerType = 'user';
        } else {
            $referringAstrologer = Astrologer::where('referral_code', $usedReferralCode)
                ->where('status', 1)
                ->where('is_verified', 1)
                ->first();

            if($referringAstrologer) {
                $referrer = $referringAstrologer;
                $referrerType = 'astrologer';
            }
        }

        if(!$referrer) {
            Log::info('CommissionService: Referrer not found', [
                'code' => $usedReferralCode,
                'order_id' => $order->id,
                'user_id' => $orderUser->id
            ]);
            return false;
        }

        // Check if referrer is an influencer (for users only)
        if($referrerType == 'user' && (!isset($referrer->is_influencer) || $referrer->is_influencer != 1)) {
            Log::info('CommissionService: Referrer is not an influencer', [
                'referrer_id' => $referrer->id,
                'referrer_type' => $referrerType,
                'is_influencer' => $referrer->is_influencer ?? 0,
                'order_id' => $order->id
            ]);
            return false;
        }

        Log::info('CommissionService: Referrer found', [
            'referrer_id' => $referrer->id,
            'referrer_type' => $referrerType,
            'referrer_name' => ($referrerType == 'user') ? ($referrer->name ?? 'NULL') : ($referrer->display_name ?? 'NULL'),
            'is_influencer' => ($referrerType == 'user') ? ($referrer->is_influencer ?? 0) : 'N/A'
        ]);

        // Check if referrer type is applicable
        if(!in_array($referrerType, $config->applicable_user_types ?? [])) {
            Log::info('CommissionService: Referrer type not applicable', ['type' => $referrerType]);
            return false;
        }

        // Prevent self-referral
        if($referrerType == 'user' && $referrer->id == $orderUser->id) {
            Log::info('CommissionService: Self-referral blocked');
            return false;
        }

        // Get order items with categories
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        if($orderItems->isEmpty()) {
            Log::info('CommissionService: No order items found');
            return false;
        }

        $commissionsCreated = false;

        try {
            DB::beginTransaction();

            foreach($orderItems as $orderItem) {
                $product = $orderItem->product;
                if(!$product) {
                    continue;
                }

                // Get product category (handle one-to-one puja case)
                $categoryId = $product->category_id;
                if(!$categoryId && $product->type == 2) {
                    // For one-to-one puja, get category from product_and_category_ids
                    $productCategoryId = \App\Model\ProductAndCategoryId::where('product_id', $product->id)->first();
                    if($productCategoryId) {
                        $categoryId = $productCategoryId->category_id;
                    }
                }

                if(!$categoryId) {
                    continue;
                }

                // Get category commission (must be active)
                $categoryCommission = CategoryCommission::where('category_id', $categoryId)
                    ->where('status', 1)
                    ->first();

                if(!$categoryCommission) {
                    Log::info('CommissionService: No category commission found', [
                        'category_id' => $categoryId,
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id
                    ]);
                    continue; // No commission configured for this category or category inactive
                }

                Log::info('CommissionService: Category commission found', [
                    'category_id' => $categoryId,
                    'commission_value' => $categoryCommission->commission_value,
                    'max_cap' => $categoryCommission->max_cap ?? 'NULL',
                    'product_id' => $product->id,
                    'order_id' => $order->id
                ]);

                // Get product commission type (percentage or fixed)
                // If product doesn't have commission_type, use config default
                $productCommissionType = $product->commission_type ?? $config->commission_mode ?? 'percentage';
                $commissionMode = strtoupper($productCommissionType) == 'FIXED' ? 'FIXED' : 'PERCENTAGE';

                // Calculate commission
                $orderAmount = $orderItem->total_price ?? $orderItem->price ?? 0;
                $commissionAmount = self::calculateCommission(
                    $orderAmount,
                    $categoryCommission->commission_value,
                    $commissionMode,
                    $categoryCommission->max_cap
                );

                if($commissionAmount <= 0) {
                    continue;
                }

                // Check if commission already exists for this order item
                $existingCommission = CommissionTransaction::where('order_id', $order->id)
                    ->where('order_item_id', $orderItem->id)
                    ->where('referrer_id', $referrer->id)
                    ->where('referrer_type', $referrerType)
                    ->first();

                if($existingCommission) {
                    continue; // Already processed
                }

                // Determine credit time based on product refund_and_cancellation
                // If refund_and_cancellation > 0, credit after that many days
                // Otherwise use config credit_time
                $creditTime = $config->credit_time ?? 'instant';
                if($product->refund_and_cancellation && $product->refund_and_cancellation > 0) {
                    $creditTime = 'delayed_' . $product->refund_and_cancellation . 'day';
                }

                // Create commission transaction
                $commissionTransaction = new CommissionTransaction();
                $commissionTransaction->referrer_id = $referrer->id;
                $commissionTransaction->referrer_type = $referrerType;
                $commissionTransaction->order_id = $order->id;
                $commissionTransaction->order_item_id = $orderItem->id;
                $commissionTransaction->category_id = $categoryId;
                $commissionTransaction->order_amount = $orderAmount;
                $commissionTransaction->commission_rate = $categoryCommission->commission_value;
                $commissionTransaction->commission_amount = $commissionAmount;
                $commissionTransaction->max_cap_applied = ($categoryCommission->max_cap && $commissionAmount >= $categoryCommission->max_cap) ? $categoryCommission->max_cap : null;
                $commissionTransaction->commission_mode = $commissionMode;
                $commissionTransaction->credit_time = $creditTime;
                $commissionTransaction->status = 'pending';
                $commissionTransaction->save();

                Log::info('CommissionService: Commission transaction created', [
                    'transaction_id' => $commissionTransaction->id,
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id,
                    'referrer_id' => $referrer->id,
                    'referrer_type' => $referrerType,
                    'commission_amount' => $commissionAmount,
                    'credit_time' => $creditTime,
                    'product_id' => $product->id,
                    'category_id' => $categoryId
                ]);

                // Credit commission based on credit_time
                if($creditTime == 'instant') {
                    self::creditCommission($commissionTransaction);
                } elseif(strpos($creditTime, 'delayed_') === 0 && strpos($creditTime, 'day') !== false) {
                    // Extract days from credit_time (e.g., 'delayed_1day' -> 1 day)
                    preg_match('/delayed_(\d+)day/', $creditTime, $matches);
                    $days = isset($matches[1]) ? (int)$matches[1] : 1;

                    // Dispatch delayed job for specified days
                    \App\Jobs\DelayedCommissionJob::dispatch($commissionTransaction)->delay(now()->addDays($days));

                    Log::info('CommissionService: Delayed commission job dispatched', [
                        'transaction_id' => $commissionTransaction->id,
                        'days' => $days,
                        'credit_time' => $creditTime,
                        'will_credit_at' => now()->addDays($days)->format('Y-m-d H:i:s')
                    ]);
                }

                $commissionsCreated = true;
            }

            DB::commit();

            if($commissionsCreated) {
                Log::info('CommissionService: Commissions processed successfully', ['order_id' => $order->id]);
            }

            return $commissionsCreated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CommissionService: Error processing commission', [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);
            return false;
        }
    }

    /**
     * Calculate commission amount
     *
     * @param float $orderAmount
     * @param float $commissionValue
     * @param string $mode PERCENTAGE or FIXED
     * @param float|null $maxCap
     * @return float
     */
    public static function calculateCommission($orderAmount, $commissionValue, $mode, $maxCap = null)
    {
        if($mode == 'PERCENTAGE') {
            $commission = $orderAmount * ($commissionValue / 100);
        } else {
            $commission = $commissionValue;
        }

        // Apply max cap if set
        if($maxCap && $commission > $maxCap) {
            $commission = $maxCap;
        }

        return round($commission, 2);
    }

    /**
     * Credit commission to wallet
     *
     * @param CommissionTransaction $transaction
     * @return bool
     */
    public static function creditCommission($transaction)
    {
        if($transaction->status != 'pending') {
            Log::info('CommissionService: Transaction not pending', [
                'transaction_id' => $transaction->id,
                'status' => $transaction->status
            ]);
            return false;
        }

        try {
            DB::beginTransaction();

            // Get referrer (user or astrologer)
            $referrer = null;
            if($transaction->referrer_type == 'user') {
                $referrer = User::find($transaction->referrer_id);
            } else {
                $referrer = Astrologer::find($transaction->referrer_id);
            }

            if(!$referrer) {
                Log::error('CommissionService: Referrer not found', [
                    'referrer_id' => $transaction->referrer_id,
                    'referrer_type' => $transaction->referrer_type
                ]);
                DB::rollBack();
                return false;
            }

            // Credit to main wallet (users.wallet or astrologers.wallet)
            $oldBalance = $referrer->wallet ?? 0;
            $referrer->wallet = ($referrer->wallet ?? 0) + $transaction->commission_amount;
            $referrer->save();

            // Also update commission wallet for tracking
            $commissionWallet = CommissionWallet::firstOrCreate(
                [
                    'user_id' => $transaction->referrer_id,
                    'user_type' => $transaction->referrer_type
                ],
                [
                    'balance' => 0,
                    'lifetime_earnings' => 0
                ]
            );

            // Credit the commission wallet
            $commissionWallet->balance += $transaction->commission_amount;
            $commissionWallet->lifetime_earnings += $transaction->commission_amount;
            $commissionWallet->save();

            // Create wallet transition for main wallet
            $walletTransition = new \App\Model\WalletTransition();
            $walletTransition->user_id = $transaction->referrer_id;
            $walletTransition->user_type = $transaction->referrer_type;
            $walletTransition->table_id = $transaction->id;
            $walletTransition->table_name = 'commission_transactions';
            $walletTransition->action = 1; // credit
            $walletTransition->status = 1; // success
            $walletTransition->amount = $transaction->commission_amount;
            $walletTransition->remark = 'Product Commission - Order #' . ($transaction->order->order_number ?? $transaction->order_id);
            $walletTransition->invoice = 0;
            $walletTransition->save();

            // Update transaction
            $transaction->status = 'credited';
            $transaction->credited_at = now();
            $transaction->save();

            // Create ledger entry
            WalletService::addLedgerEntry(
                'commission',
                $transaction->referrer_id,
                $transaction->referrer_type,
                'credit',
                $transaction->commission_amount,
                $commissionWallet->balance,
                'commission_transaction',
                $transaction->id,
                'Product Commission - Order #' . ($transaction->order->order_number ?? $transaction->order_id)
            );

            Log::info('CommissionService: Commission credited successfully', [
                'transaction_id' => $transaction->id,
                'referrer_id' => $transaction->referrer_id,
                'referrer_type' => $transaction->referrer_type,
                'commission_amount' => $transaction->commission_amount,
                'old_wallet_balance' => $oldBalance,
                'new_wallet_balance' => $referrer->wallet,
                'commission_wallet_balance' => $commissionWallet->balance
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CommissionService: Error crediting commission', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Cancel all commissions for an order
     *
     * @param Order $order
     * @return bool
     */
    public static function cancelOrderCommissions($order)
    {
        $commissions = CommissionTransaction::where('order_id', $order->id)
            ->where('status', 'credited')
            ->get();

        $cancelled = false;
        foreach($commissions as $commission) {
            if(self::cancelCommission($commission)) {
                $cancelled = true;
            }
        }

        return $cancelled;
    }

    /**
     * Credit pending commissions when order is completed
     *
     * @param Order $order
     * @return bool
     */
    public static function creditPendingCommissions($order)
    {
        $commissions = CommissionTransaction::where('order_id', $order->id)
            ->where('status', 'pending')
            ->where('credit_time', 'after_completion')
            ->get();

        $credited = false;
        foreach($commissions as $commission) {
            if(self::creditCommission($commission)) {
                $credited = true;
            }
        }

        return $credited;
    }

    /**
     * Cancel commission on refund
     *
     * @param CommissionTransaction $transaction
     * @return bool
     */
    public static function cancelCommission($transaction)
    {
        if($transaction->status != 'credited') {
            return false;
        }

        try {
            DB::beginTransaction();

            // Get commission wallet
            $wallet = CommissionWallet::where('user_id', $transaction->referrer_id)
                ->where('user_type', $transaction->referrer_type)
                ->first();

            if($wallet && $wallet->balance >= $transaction->commission_amount) {
                // Debit the wallet
                $wallet->balance -= $transaction->commission_amount;
                $wallet->save();

                // Update transaction
                $transaction->status = 'refunded';
                $transaction->refunded_at = now();
                $transaction->save();

                // Create ledger entry
                WalletService::addLedgerEntry(
                    'commission',
                    $transaction->referrer_id,
                    $transaction->referrer_type,
                    'debit',
                    $transaction->commission_amount,
                    $wallet->balance,
                    'commission_transaction',
                    $transaction->id,
                    'Commission Refund - Order #' . ($transaction->order->order_number ?? $transaction->order_id)
                );
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CommissionService: Error cancelling commission', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
