<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{ReferralTransaction, ReferralWallet, CommissionTransaction, CommissionWallet, WalletLedger};
use App\Services\WalletService;

class ReferralController extends Controller
{
    /**
     * Refer & Earn page
     */
    public function referEarn()
    {
        $user = auth()->guard('web')->user();
        $nav = 'refer-earn';

        // Get wallet balances
        $referralWallet = ReferralWallet::where('user_id', $user->id)
            ->where('user_type', 'user')
            ->first();

        $commissionWallet = CommissionWallet::where('user_id', $user->id)
            ->where('user_type', 'user')
            ->first();

        $referralBalance = $referralWallet ? $referralWallet->balance : 0;
        $commissionBalance = $commissionWallet ? $commissionWallet->balance : 0;
        $referralLifetime = $referralWallet ? $referralWallet->lifetime_earnings : 0;
        $commissionLifetime = $commissionWallet ? $commissionWallet->lifetime_earnings : 0;
        $totalEarnings = $referralLifetime + $commissionLifetime;

        // Get total referrals count
        $totalReferrals = ReferralTransaction::where('referrer_id', $user->id)
            ->where('referrer_type', 'user')
            ->count();

        $appReferrals = [
            'total' => $totalReferrals,
            'credited_count' => (int) ReferralTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'credited')
                ->count(),
            'pending_count' => (int) ReferralTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'pending')
                ->count(),
            'credited_amount' => (float) ReferralTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'credited')
                ->sum('reward_amount'),
        ];

        $productCommissions = [
            'total_count' => (int) CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->count(),
            'credited_count' => (int) CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'credited')
                ->count(),
            'pending_count' => (int) CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'pending')
                ->count(),
            'credited_amount' => (float) CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'credited')
                ->sum('commission_amount'),
            'pending_amount' => (float) CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'pending')
                ->sum('commission_amount'),
            'orders' => (int) CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->distinct('order_id')
                ->count('order_id'),
        ];

        $walletLedger = [
            'total' => (int) WalletLedger::where('user_id', $user->id)
                ->where('user_type', 'user')
                ->count(),
            'last_at' => WalletLedger::where('user_id', $user->id)
                ->where('user_type', 'user')
                ->max('created_at'),
        ];

        $latestCommission = CommissionTransaction::where('referrer_id', $user->id)
            ->where('referrer_type', 'user')
            ->orderBy('created_at', 'desc')
            ->first();

        // Use actual user registration route (GET user/register)
        $referralLink = route('user.register', ['ref' => ($user->referral_code ?? '')]);

        return view(themeView().'account.refer-earn', compact(
            'nav',
            'user',
            'referralBalance',
            'commissionBalance',
            'referralLifetime',
            'commissionLifetime',
            'totalEarnings',
            'totalReferrals',
            'appReferrals',
            'productCommissions',
            'walletLedger',
            'latestCommission',
            'referralLink'
        ));
    }

    /**
     * App Referrals listing
     */
    public function appReferrals(Request $request)
    {
        $user = auth()->guard('web')->user();
        $nav = 'app-referrals';

        $query = ReferralTransaction::where('referrer_id', $user->id)
            ->where('referrer_type', 'user')
            ->with('referred', 'order')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if($request->status) {
            $query->where('status', $request->status);
        }

        if($request->reward_trigger) {
            $query->where('reward_trigger', $request->reward_trigger);
        }

        $referrals = $query->paginate(20);

        // Calculate stats
        $stats = [
            'total' => ReferralTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->count(),
            'credited' => ReferralTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'credited')
                ->count(),
            'pending' => ReferralTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'pending')
                ->count(),
        ];

        return view(themeView().'account.app-referrals', compact('nav', 'referrals', 'stats'));
    }
}
