<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{ReferralWallet, CommissionWallet, WalletLedger};
use App\Services\WalletService;

class WalletController extends Controller
{
    /**
     * Earnings Dashboard
     */
    public function earnings(Request $request)
    {
        $user = auth()->guard('web')->user();
        $nav = 'earnings';

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
        $totalEarnings = ($referralWallet ? $referralWallet->lifetime_earnings : 0) +
                        ($commissionWallet ? $commissionWallet->lifetime_earnings : 0);

        // Get stats
        $stats = [
            'total_referrals' => \App\Model\ReferralTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->count(),
            'active_referrals' => \App\Model\ReferralTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'credited')
                ->count(),
            'commission_orders' => \App\Model\CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->distinct('order_id')
                ->count('order_id'),
        ];

        // All transactions (paginated) for Earnings screen
        $walletType = $request->get('wallet_type') ?: $request->get('wallet');
        $transactionType = $request->get('transaction_type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $ledgerQuery = WalletLedger::where('user_id', $user->id)
            ->where('user_type', 'user')
            ->orderBy('created_at', 'desc');

        if (in_array($walletType, ['referral', 'commission'], true)) {
            $ledgerQuery->where('wallet_type', $walletType);
        }

        if (in_array($transactionType, ['credit', 'debit'], true)) {
            $ledgerQuery->where('transaction_type', $transactionType);
        }

        if ($dateFrom) {
            $ledgerQuery->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ?", [$dateFrom]);
        }

        if ($dateTo) {
            $ledgerQuery->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ?", [$dateTo]);
        }

        $ledgerEntries = $ledgerQuery->paginate(20)->withQueryString();

        return view(themeView().'account.earnings', compact(
            'nav',
            'user',
            'referralBalance',
            'commissionBalance',
            'referralLifetime',
            'commissionLifetime',
            'totalEarnings',
            'stats',
            'ledgerEntries',
            'walletType',
            'transactionType',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Wallet Ledger
     */
    public function ledger(Request $request)
    {
        $user = auth()->guard('web')->user();
        $nav = 'wallet-ledger';

        // Get wallet balances
        $referralWallet = ReferralWallet::where('user_id', $user->id)
            ->where('user_type', 'user')
            ->first();

        $commissionWallet = CommissionWallet::where('user_id', $user->id)
            ->where('user_type', 'user')
            ->first();

        $referralBalance = $referralWallet ? $referralWallet->balance : 0;
        $commissionBalance = $commissionWallet ? $commissionWallet->balance : 0;

        // Get ledger entries
        $query = WalletLedger::where('user_id', $user->id)
            ->where('user_type', 'user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if($request->wallet_type) {
            $query->where('wallet_type', $request->wallet_type);
        }

        if($request->transaction_type) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if($request->date_from) {
            $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ?", [$request->date_from]);
        }

        if($request->date_to) {
            $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ?", [$request->date_to]);
        }

        $ledgerEntries = $query->paginate(20);

        return view(themeView().'account.wallet-ledger', compact('nav', 'referralBalance', 'commissionBalance', 'ledgerEntries'));
    }
}
