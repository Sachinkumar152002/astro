<?php

namespace App\Services;

use App\Model\WalletLedger;
use Illuminate\Support\Facades\Log;

class WalletService
{
    /**
     * Add entry to wallet ledger
     *
     * @param string $walletType 'referral' or 'commission'
     * @param int $userId
     * @param string $userType 'user' or 'astrologer'
     * @param string $transactionType 'credit' or 'debit'
     * @param float $amount
     * @param float $balanceAfter
     * @param string $referenceType
     * @param int|null $referenceId
     * @param string|null $description
     * @return WalletLedger
     */
    public static function addLedgerEntry(
        $walletType,
        $userId,
        $userType,
        $transactionType,
        $amount,
        $balanceAfter,
        $referenceType,
        $referenceId = null,
        $description = null
    ) {
        try {
            $ledger = new WalletLedger();
            $ledger->wallet_type = $walletType;
            $ledger->user_id = $userId;
            $ledger->user_type = $userType;
            $ledger->transaction_type = $transactionType;
            $ledger->amount = $amount;
            $ledger->balance_after = $balanceAfter;
            $ledger->reference_type = $referenceType;
            $ledger->reference_id = $referenceId;
            $ledger->description = $description;
            $ledger->save();

            return $ledger;
        } catch (\Exception $e) {
            Log::error('WalletService: Error adding ledger entry', [
                'error' => $e->getMessage(),
                'data' => func_get_args()
            ]);
            throw $e;
        }
    }

    /**
     * Get wallet balance
     *
     * @param int $userId
     * @param string $userType
     * @param string $walletType
     * @return float
     */
    public static function getBalance($userId, $userType, $walletType)
    {
        if($walletType == 'referral') {
            $wallet = \App\Model\ReferralWallet::where('user_id', $userId)
                ->where('user_type', $userType)
                ->first();
        } else {
            $wallet = \App\Model\CommissionWallet::where('user_id', $userId)
                ->where('user_type', $userType)
                ->first();
        }

        return $wallet ? $wallet->balance : 0;
    }

    /**
     * Get total earnings (lifetime)
     *
     * @param int $userId
     * @param string $userType
     * @param string $walletType
     * @return float
     */
    public static function getLifetimeEarnings($userId, $userType, $walletType)
    {
        if($walletType == 'referral') {
            $wallet = \App\Model\ReferralWallet::where('user_id', $userId)
                ->where('user_type', $userType)
                ->first();
        } else {
            $wallet = \App\Model\CommissionWallet::where('user_id', $userId)
                ->where('user_type', $userType)
                ->first();
        }

        return $wallet ? $wallet->lifetime_earnings : 0;
    }
}
