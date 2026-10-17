<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ReferralTransaction;

class ReferralTransactionController extends Controller
{
    private $module = 'referral_transactions';
    private $moduleName = 'Referral Transactions';

    public function index(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = 'referral_transactions';
        $breads = [
            ['name' => $this->moduleName]
        ];
        return view('admin.referral-transactions.list', compact('nav', 'breads'));
    }

    public function all(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');

        $results = ReferralTransaction::with(['referrer', 'referred', 'order'])
            ->orderBy($request->sort_by ?: 'created_at', $request->sort_dir ?: 'desc');

        if($referrer_type = $request->referrer_type) {
            $results->where('referrer_type', $referrer_type);
        }

        if($status = $request->status) {
            $results->where('status', $status);
        }

        if($reward_trigger = $request->reward_trigger) {
            $results->where('reward_trigger', $reward_trigger);
        }

        if($created_at_from = $request->created_at_from) {
            $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ?", [$created_at_from]);
        }

        if($created_at_to = $request->created_at_to) {
            $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ?", [$created_at_to]);
        }

        $results = $results->paginate(13);

        $data = [
            'rows' => view('admin.referral-transactions.listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
            'total' => $results->total()
        ];

        return response()->json($data, 200);
    }
}
