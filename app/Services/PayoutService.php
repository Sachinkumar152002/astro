<?php

namespace App\Services;

use App\Model\{ReferralWithdrawal, ReferralWallet, CommissionWallet};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutService
{
    /**
     * Create withdrawal request
     *
     * @param int $astrologerId
     * @param string $walletType 'referral', 'commission', or 'both'
     * @param float|null $referralAmount
     * @param float|null $commissionAmount
     * @return ReferralWithdrawal|false
     */
    public static function createWithdrawalRequest($astrologerId, $walletType, $referralAmount = null, $commissionAmount = null)
    {
        try {
            DB::beginTransaction();

            // Get wallet balances
            $referralWallet = ReferralWallet::where('user_id', $astrologerId)
                ->where('user_type', 'astrologer')
                ->first();

            $commissionWallet = CommissionWallet::where('user_id', $astrologerId)
                ->where('user_type', 'astrologer')
                ->first();

            $referralBalance = $referralWallet ? $referralWallet->balance : 0;
            $commissionBalance = $commissionWallet ? $commissionWallet->balance : 0;

            // Determine amounts based on wallet type
            if($walletType == 'referral') {
                $referralAmount = $referralAmount ?? $referralBalance;
                $commissionAmount = 0;
            } elseif($walletType == 'commission') {
                $referralAmount = 0;
                $commissionAmount = $commissionAmount ?? $commissionBalance;
            } else { // both
                $referralAmount = $referralAmount ?? $referralBalance;
                $commissionAmount = $commissionAmount ?? $commissionBalance;
            }

            // Validate amounts
            if($referralAmount > $referralBalance || $commissionAmount > $commissionBalance) {
                throw new \Exception('Insufficient balance');
            }

            $totalAmount = $referralAmount + $commissionAmount;

            if($totalAmount <= 0) {
                throw new \Exception('Withdrawal amount must be greater than 0');
            }

            // Minimum withdrawal check (can be configured)
            $minWithdrawal = 100; // Can be moved to config
            if($totalAmount < $minWithdrawal) {
                throw new \Exception('Minimum withdrawal amount is ₹' . $minWithdrawal);
            }

            // Create withdrawal request
            $withdrawal = new ReferralWithdrawal();
            $withdrawal->astrologer_id = $astrologerId;
            $withdrawal->wallet_type = $walletType;
            $withdrawal->referral_amount = $referralAmount;
            $withdrawal->commission_amount = $commissionAmount;
            $withdrawal->total_amount = $totalAmount;
            $withdrawal->status = 'pending';
            $withdrawal->save();

            DB::commit();

            Log::info('PayoutService: Withdrawal request created', [
                'withdrawal_id' => $withdrawal->id,
                'astrologer_id' => $astrologerId,
                'total_amount' => $totalAmount
            ]);

            return $withdrawal;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PayoutService: Error creating withdrawal request', [
                'error' => $e->getMessage(),
                'astrologer_id' => $astrologerId
            ]);
            return false;
        }
    }

    /**
     * Process withdrawal (debit wallets)
     *
     * @param ReferralWithdrawal $withdrawal
     * @return bool
     */
    public static function processWithdrawal($withdrawal)
    {
        if($withdrawal->status != 'approved') {
            return false;
        }

        try {
            DB::beginTransaction();

            // Debit referral wallet if applicable
            if($withdrawal->referral_amount > 0) {
                $referralWallet = ReferralWallet::where('user_id', $withdrawal->astrologer_id)
                    ->where('user_type', 'astrologer')
                    ->first();

                if($referralWallet && $referralWallet->balance >= $withdrawal->referral_amount) {
                    $referralWallet->balance -= $withdrawal->referral_amount;
                    $referralWallet->save();

                    // Create ledger entry
                    WalletService::addLedgerEntry(
                        'referral',
                        $withdrawal->astrologer_id,
                        'astrologer',
                        'debit',
                        $withdrawal->referral_amount,
                        $referralWallet->balance,
                        'withdrawal',
                        $withdrawal->id,
                        'Withdrawal - Referral Wallet'
                    );
                }
            }

            // Debit commission wallet if applicable
            if($withdrawal->commission_amount > 0) {
                $commissionWallet = CommissionWallet::where('user_id', $withdrawal->astrologer_id)
                    ->where('user_type', 'astrologer')
                    ->first();

                if($commissionWallet && $commissionWallet->balance >= $withdrawal->commission_amount) {
                    $commissionWallet->balance -= $withdrawal->commission_amount;
                    $commissionWallet->save();

                    // Create ledger entry
                    WalletService::addLedgerEntry(
                        'commission',
                        $withdrawal->astrologer_id,
                        'astrologer',
                        'debit',
                        $withdrawal->commission_amount,
                        $commissionWallet->balance,
                        'withdrawal',
                        $withdrawal->id,
                        'Withdrawal - Commission Wallet'
                    );
                }
            }

            $withdrawal->status = 'processed';
            $withdrawal->processed_at = now();
            $withdrawal->save();

            DB::commit();

            Log::info('PayoutService: Withdrawal processed', [
                'withdrawal_id' => $withdrawal->id
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PayoutService: Error processing withdrawal', [
                'error' => $e->getMessage(),
                'withdrawal_id' => $withdrawal->id
            ]);
            return false;
        }
    }
}
