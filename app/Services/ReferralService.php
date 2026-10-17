<?php

namespace App\Services;

use App\Model\{ReferralConfiguration, ReferralTransaction, ReferralWallet};
use App\User;
use App\Model\Astrologer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    /**
     * Process app referral reward when trigger condition is met
     *
     * @param mixed $referredUser The user/astrologer who was referred
     * @param string $referredType 'user' or 'astrologer'
     * @param int|null $orderId Order ID if trigger is first_purchase
     * @return bool Returns true if reward was processed, false otherwise
     */
    public static function processReferralReward($referredUser, $referredType = 'user', $orderId = null)
    {
        // Get active referral configuration
        $config = ReferralConfiguration::where('status', 1)
            ->where('enable_app_referral', true)
            ->first();

        if(!$config) {
            Log::info('ReferralService: App referral is disabled or not configured');
            return false;
        }

        // Check if user type is applicable
        if(!in_array($referredType, $config->applicable_user_types ?? [])) {
            Log::info('ReferralService: User type not applicable', ['type' => $referredType]);
            return false;
        }

        // Get the referral code used by the referred user
        $usedReferralCode = $referredUser->used_referral_code ?? null;
        if(!$usedReferralCode || trim($usedReferralCode) == '') {
            Log::info('ReferralService: No referral code used');
            return false;
        }

        // Determine trigger
        $trigger = $config->reward_trigger;
        if($trigger == 'first_purchase' && !$orderId) {
            return false; // First purchase trigger requires order
        }

        // Find the referrer
        $referrer = null;
        $referrerType = null;

        // Check if it's a user's referral code
        $referringUser = User::where('referral_code', $usedReferralCode)
            ->where('status', 1)
            ->first();

        if($referringUser) {
            $referrer = $referringUser;
            $referrerType = 'user';
        } else {
            // Check if it's an astrologer's referral code
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
            Log::info('ReferralService: Referrer not found', ['code' => $usedReferralCode]);
            return false;
        }

        // Check if referrer type is applicable
        if(!in_array($referrerType, $config->applicable_user_types ?? [])) {
            Log::info('ReferralService: Referrer type not applicable', ['type' => $referrerType]);
            return false;
        }

        // Prevent self-referral
        if($referrerType == $referredType) {
            if($referrerType == 'user' && $referrer->id == $referredUser->id) {
                Log::info('ReferralService: Self-referral blocked');
                return false;
            }
            if($referrerType == 'astrologer' && $referrer->id == $referredUser->id) {
                Log::info('ReferralService: Self-referral blocked');
                return false;
            }
        }

        // Check max referrals per user
        if($config->max_referrals_per_user) {
            $totalReferrals = ReferralTransaction::where('referrer_id', $referrer->id)
                ->where('referrer_type', $referrerType)
                ->where('status', 'credited')
                ->count();

            if($totalReferrals >= $config->max_referrals_per_user) {
                Log::info('ReferralService: Max referrals limit reached', [
                    'referrer_id' => $referrer->id,
                    'total' => $totalReferrals,
                    'limit' => $config->max_referrals_per_user
                ]);
                return false;
            }
        }

        // Check max total earning
        if($config->max_total_earning) {
            $referralWallet = ReferralWallet::where('user_id', $referrer->id)
                ->where('user_type', $referrerType)
                ->first();

            $lifetimeEarnings = $referralWallet ? $referralWallet->lifetime_earnings : 0;
            if($lifetimeEarnings >= $config->max_total_earning) {
                Log::info('ReferralService: Max total earning limit reached', [
                    'referrer_id' => $referrer->id,
                    'earnings' => $lifetimeEarnings,
                    'limit' => $config->max_total_earning
                ]);
                return false;
            }
        }

        // Check if reward already exists for this referral
        $existingTransaction = ReferralTransaction::where('referrer_id', $referrer->id)
            ->where('referrer_type', $referrerType)
            ->where('referred_id', $referredUser->id)
            ->where('referred_type', $referredType)
            ->where('reward_trigger', $trigger)
            ->first();

        if($existingTransaction) {
            Log::info('ReferralService: Transaction already exists', ['transaction_id' => $existingTransaction->id]);
            return false;
        }

        try {
            DB::beginTransaction();

            // Calculate expiry date if configured
            $expiryDate = null;
            if($config->reward_expiry_days) {
                $expiryDate = now()->addDays($config->reward_expiry_days);
            }

            // Create referral transaction
            $transaction = new ReferralTransaction();
            $transaction->referrer_id = $referrer->id;
            $transaction->referrer_type = $referrerType;
            $transaction->referred_id = $referredUser->id;
            $transaction->referred_type = $referredType;
            $transaction->reward_amount = $config->reward_amount;
            $transaction->reward_trigger = $trigger;
            $transaction->status = 'pending';
            $transaction->expiry_date = $expiryDate;
            $transaction->order_id = $orderId;
            $transaction->save();

            // Credit the reward immediately (or can be done via cron for pending rewards)
            self::creditReferralReward($transaction);

            DB::commit();

            Log::info('ReferralService: Reward processed successfully', [
                'transaction_id' => $transaction->id,
                'referrer_id' => $referrer->id,
                'referred_id' => $referredUser->id
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ReferralService: Error processing reward', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Credit referral reward to wallet
     *
     * @param ReferralTransaction $transaction
     * @return bool
     */
    public static function creditReferralReward($transaction)
    {
        if($transaction->status != 'pending') {
            return false;
        }

        // Check expiry
        if($transaction->expiry_date && now() > $transaction->expiry_date) {
            $transaction->status = 'expired';
            $transaction->save();
            return false;
        }

        try {
            DB::beginTransaction();

            // Get or create referral wallet
            $wallet = ReferralWallet::firstOrCreate(
                [
                    'user_id' => $transaction->referrer_id,
                    'user_type' => $transaction->referrer_type
                ],
                [
                    'balance' => 0,
                    'lifetime_earnings' => 0
                ]
            );

            // Credit the wallet
            $wallet->balance += $transaction->reward_amount;
            $wallet->lifetime_earnings += $transaction->reward_amount;
            $wallet->save();

            // Update transaction
            $transaction->status = 'credited';
            $transaction->credited_at = now();
            $transaction->save();

            // Create ledger entry
            WalletService::addLedgerEntry(
                'referral',
                $transaction->referrer_id,
                $transaction->referrer_type,
                'credit',
                $transaction->reward_amount,
                $wallet->balance,
                'referral_transaction',
                $transaction->id,
                'App Referral Reward - ' . ucfirst($transaction->reward_trigger)
            );

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ReferralService: Error crediting reward', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
