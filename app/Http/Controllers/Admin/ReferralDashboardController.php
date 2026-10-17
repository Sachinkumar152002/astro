<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\{
    ReferralConfiguration,
    ProductReferralConfiguration,
    CategoryCommission,
    ReferralTransaction,
    CommissionTransaction,
    ReferralWithdrawal,
    ReferralVisit
};

class ReferralDashboardController extends Controller
{
    public function index(Request $request)
    {
        $canAny =
            adminCan('referral_configuration', 'view') ||
            adminCan('product_referral_configuration', 'view') ||
            adminCan('category_commissions', 'view') ||
            adminCan('referral_transactions', 'view') ||
            adminCan('commission_transactions', 'view') ||
            adminCan('referral_withdrawals', 'view');

        if(!$canAny) return dd('No Permission');

        $nav = 'referral_dashboard';
        $breads = [
            ['name' => 'Referral Dashboard']
        ];

        $appConfig = ReferralConfiguration::first();
        $productConfig = ProductReferralConfiguration::first();

        // -----------------------------
        // Top KPIs (At a Glance)
        // -----------------------------
        $userReferralSignups = (int) DB::table('users')
            ->whereNotNull('used_referral_code')
            ->where('used_referral_code', '!=', '')
            ->count();

        $astrologerReferralSignups = (int) DB::table('astrologers')
            ->whereNotNull('used_referral_code')
            ->where('used_referral_code', '!=', '')
            ->count();

        $totalReferrals = $userReferralSignups + $astrologerReferralSignups;

        // Real Visitors: unique visitor_ids (all-time)
        $referralVisitors = (int) ReferralVisit::distinct()->count('visitor_id');

        // Orders from users who used a referral code
        $eligibleOrders = DB::table('orders as o')
            ->join('users as u', 'u.id', '=', 'o.user_id')
            ->whereNotNull('u.used_referral_code')
            ->where('u.used_referral_code', '!=', '')
            ->whereNotIn('o.order_status', ['cancel', 'return']);

        $firstPurchaseUsers = (int) (clone $eligibleOrders)->distinct()->count('o.user_id');

        // Repeat purchase users (>= 2 eligible orders)
        $repeatPurchaseUsers = (int) DB::query()
            ->fromSub(
                (clone $eligibleOrders)
                    ->selectRaw('o.user_id')
                    ->groupBy('o.user_id')
                    ->havingRaw('COUNT(o.id) >= 2'),
                't'
            )
            ->count();

        $totalRewardsPaid = (float) ReferralTransaction::where('status', 'credited')->sum('reward_amount');
        $totalCommissionPaid = (float) CommissionTransaction::where('status', 'credited')->sum('commission_amount');

        // Referral-driven sales approximation: sum of commission_transactions order_amount (exclude refunded)
        $referralSales = (float) CommissionTransaction::where('status', '!=', 'refunded')->sum('order_amount');

        // Net revenue from referrals (approx): Sales - Commission Paid - Rewards Paid
        $netRevenueFromReferrals = $referralSales - $totalCommissionPaid - $totalRewardsPaid;

        // Funnel View (Performance Flow)
        $funnel = [
            'visitors' => $referralVisitors,
            'signups' => $totalReferrals,
            'first_purchase' => $firstPurchaseUsers,
            'repeat_purchase' => $repeatPurchaseUsers
        ];

        // -----------------------------
        // Category Performance (Revenue Impact)
        // Sales, Commission, Profit (Profit = Sales - Commission)
        // -----------------------------
        $categoryPerformance = DB::table('commission_transactions as ct')
            ->leftJoin('product_categories as pc', 'pc.id', '=', 'ct.category_id')
            ->where('ct.status', '!=', 'refunded')
            ->selectRaw("
                ct.category_id,
                COALESCE(pc.name, CONCAT('Category #', ct.category_id)) as category_name,
                COALESCE(SUM(ct.order_amount), 0) as sales,
                COALESCE(SUM(ct.commission_amount), 0) as commission
            ")
            ->groupBy('ct.category_id', 'pc.name')
            ->orderByRaw('sales DESC')
            ->limit(10)
            ->get();

        // -----------------------------
        // Top Referrers (Leaderboard)
        // total referrals (count), earnings (rewards + commissions credited)
        // -----------------------------
        $referrerReferralAgg = DB::table('referral_transactions')
            ->selectRaw("
                referrer_type,
                referrer_id,
                COUNT(*) as total_referrals,
                COALESCE(SUM(CASE WHEN status = 'credited' THEN reward_amount ELSE 0 END), 0) as reward_earnings
            ")
            ->groupBy('referrer_type', 'referrer_id')
            ->get();

        $referrerCommissionAgg = DB::table('commission_transactions')
            ->selectRaw("
                referrer_type,
                referrer_id,
                COALESCE(SUM(CASE WHEN status = 'credited' THEN commission_amount ELSE 0 END), 0) as commission_earnings
            ")
            ->groupBy('referrer_type', 'referrer_id')
            ->get()
            ->keyBy(function ($row) {
                return ($row->referrer_type ?? '-') . '-' . ($row->referrer_id ?? 0);
            });

        $userIds = [];
        $astrologerIds = [];
        foreach ($referrerReferralAgg as $row) {
            if (($row->referrer_type ?? '') === 'user') $userIds[] = (int) $row->referrer_id;
            if (($row->referrer_type ?? '') === 'astrologer') $astrologerIds[] = (int) $row->referrer_id;
        }

        $userNames = DB::table('users')
            ->whereIn('id', array_values(array_unique($userIds)))
            ->pluck('name', 'id');

        $astrologers = DB::table('astrologers')
            ->whereIn('id', array_values(array_unique($astrologerIds)))
            ->get(['id', 'display_name', 'firstname', 'lastname'])
            ->keyBy('id');

        $topReferrers = [];
        foreach ($referrerReferralAgg as $row) {
            $key = ($row->referrer_type ?? '-') . '-' . ($row->referrer_id ?? 0);
            $commissionEarn = (float) (($referrerCommissionAgg[$key]->commission_earnings ?? 0));
            $rewardEarn = (float) ($row->reward_earnings ?? 0);
            $earnings = $rewardEarn + $commissionEarn;

            $name = 'N/A';
            if (($row->referrer_type ?? '') === 'user') {
                $name = $userNames[$row->referrer_id] ?? 'N/A';
            } elseif (($row->referrer_type ?? '') === 'astrologer') {
                $a = $astrologers[$row->referrer_id] ?? null;
                $name = $a ? ($a->display_name ?: trim(($a->firstname ?? '') . ' ' . ($a->lastname ?? '')) ?: 'N/A') : 'N/A';
            }

            $topReferrers[] = (object) [
                'name' => $name,
                'type' => $row->referrer_type ?? '-',
                'total_referrals' => (int) ($row->total_referrals ?? 0),
                'earnings' => $earnings
            ];
        }

        usort($topReferrers, function ($a, $b) {
            return ($b->earnings <=> $a->earnings);
        });
        $topReferrers = array_slice($topReferrers, 0, 10);

        // -----------------------------
        // Pending Liabilities
        // -----------------------------
        $pendingAppRewards = ReferralTransaction::where('status', 'pending')
            ->selectRaw('COUNT(*) as cnt, COALESCE(SUM(reward_amount), 0) as amt')
            ->first();

        $pendingCommissions = CommissionTransaction::where('status', 'pending')
            ->selectRaw('COUNT(*) as cnt, COALESCE(SUM(commission_amount), 0) as amt')
            ->first();

        return view('admin.referral-dashboard.index', compact(
            'nav',
            'breads',
            'appConfig',
            'productConfig',
            'referralVisitors',
            'totalReferrals',
            'firstPurchaseUsers',
            'totalRewardsPaid',
            'totalCommissionPaid',
            'netRevenueFromReferrals',
            'funnel',
            'categoryPerformance',
            'topReferrers',
            'pendingAppRewards',
            'pendingCommissions'
        ));
    }
}
