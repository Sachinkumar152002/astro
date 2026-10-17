<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{ReferralWithdrawal, ReferralWallet, CommissionWallet};
use App\Services\PayoutService;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    /**
     * Withdrawal page for astrologers
     */
    public function index()
    {
        $astrologer = auth()->guard('astrologer')->user();
        $nav = 'withdrawal';

        // Get wallet balances
        $referralWallet = ReferralWallet::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->first();

        $commissionWallet = CommissionWallet::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->first();

        $referralBalance = $referralWallet ? $referralWallet->balance : 0;
        $commissionBalance = $commissionWallet ? $commissionWallet->balance : 0;

        // Get withdrawal history
        $withdrawals = ReferralWithdrawal::where('astrologer_id', $astrologer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(themeView().'astrologers.account.referral-withdrawal', compact('nav', 'astrologer', 'referralBalance', 'commissionBalance', 'withdrawals'));
    }

    /**
     * Submit withdrawal request
     */
    public function submit(Request $request)
    {
        $astrologer = auth()->guard('astrologer')->user();

        $validations = [
            'wallet_type' => 'required|in:referral,commission,both',
            'withdrawal_amount' => 'required|numeric|min:100',
        ];

        // If both wallets, require breakdown
        if($request->wallet_type == 'both') {
            $validations['referral_amount'] = 'required|numeric|min:0';
            $validations['commission_amount'] = 'required|numeric|min:0';
        }

        $request->validate($validations);

        // Get wallet balances
        $referralWallet = ReferralWallet::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->first();

        $commissionWallet = CommissionWallet::where('user_id', $astrologer->id)
            ->where('user_type', 'astrologer')
            ->first();

        $referralBalance = $referralWallet ? $referralWallet->balance : 0;
        $commissionBalance = $commissionWallet ? $commissionWallet->balance : 0;

        // Determine amounts
        if($request->wallet_type == 'both') {
            $referralAmount = $request->referral_amount;
            $commissionAmount = $request->commission_amount;
        } elseif($request->wallet_type == 'referral') {
            $referralAmount = $request->withdrawal_amount;
            $commissionAmount = 0;
        } else {
            $referralAmount = 0;
            $commissionAmount = $request->withdrawal_amount;
        }

        // Validate amounts
        if($referralAmount > $referralBalance || $commissionAmount > $commissionBalance) {
            return response()->json(['errors' => ['general' => ['Insufficient balance']]], 422);
        }

        $totalAmount = $referralAmount + $commissionAmount;
        if($totalAmount < 100) {
            return response()->json(['errors' => ['general' => ['Minimum withdrawal amount is ₹100']]], 422);
        }

        // Create withdrawal request
        $withdrawal = PayoutService::createWithdrawalRequest(
            $astrologer->id,
            $request->wallet_type,
            $referralAmount,
            $commissionAmount
        );

        if(!$withdrawal) {
            return response()->json(['errors' => ['general' => ['Failed to create withdrawal request']]], 422);
        }

        return response()->json([
            'message' => 'Withdrawal request submitted successfully',
            'redirectto' => route('astrologer.withdrawal')
        ], 200);
    }
}
