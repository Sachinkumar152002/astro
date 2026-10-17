<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{CommissionTransaction, CommissionWallet};
use App\Services\WalletService;
use App\Model\Category;

class CommissionController extends Controller
{
    /**
     * Product Commissions listing
     */
    public function index(Request $request)
    {
        $user = auth()->guard('web')->user();
        $nav = 'commissions';

        $query = CommissionTransaction::where('referrer_id', $user->id)
            ->where('referrer_type', 'user')
            ->with('order', 'category', 'orderItem.product')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if($request->status) {
            $query->where('status', $request->status);
        }

        if($request->date_from) {
            $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ?", [$request->date_from]);
        }

        if($request->date_to) {
            $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ?", [$request->date_to]);
        }

        $commissions = $query->paginate(20)->withQueryString();

        // Categories for filter (only those present for this user)
        $categoryIds = CommissionTransaction::where('referrer_id', $user->id)
            ->where('referrer_type', 'user')
            ->whereNotNull('category_id')
            ->distinct()
            ->pluck('category_id')
            ->filter()
            ->values()
            ->all();

        $categories = !empty($categoryIds)
            ? Category::whereIn('id', $categoryIds)->orderBy('name')->get(['id', 'name'])
            : collect();

        // Calculate stats
        $stats = [
            'total' => CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->sum('commission_amount'),
            'credited' => CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'credited')
                ->sum('commission_amount'),
            'pending' => CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->where('status', 'pending')
                ->sum('commission_amount'),
            'total_orders' => CommissionTransaction::where('referrer_id', $user->id)
                ->where('referrer_type', 'user')
                ->distinct('order_id')
                ->count('order_id'),
        ];

        return view(themeView().'account.commissions', compact('nav', 'commissions', 'stats', 'categories'));
    }

    /**
     * Commission detail page
     */
    public function detail($id)
    {
        $user = auth()->guard('web')->user();
        $nav = 'commissions';

        $commission = CommissionTransaction::where('id', $id)
            ->where('referrer_id', $user->id)
            ->where('referrer_type', 'user')
            ->with('order', 'category', 'orderItem.product')
            ->firstOrFail();

        return view(themeView().'account.commission-detail', compact('nav', 'commission'));
    }
}
