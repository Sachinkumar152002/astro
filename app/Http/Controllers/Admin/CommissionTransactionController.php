<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CommissionTransaction;

class CommissionTransactionController extends Controller
{
    private $module = 'commission_transactions';
    private $moduleName = 'Commission Transactions';

    public function index(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = 'commission_transactions';
        $breads = [
            ['name' => $this->moduleName]
        ];
        return view('admin.commission-transactions.list', compact('nav', 'breads'));
    }

    public function all(Request $request)
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');

        $results = CommissionTransaction::with(['referrer', 'order', 'category'])
            ->orderBy($request->sort_by ?: 'created_at', $request->sort_dir ?: 'desc');

        if($referrer_type = $request->referrer_type) {
            $results->where('referrer_type', $referrer_type);
        }

        if($category_id = $request->category_id) {
            $results->where('category_id', $category_id);
        }

        if($status = $request->status) {
            $results->where('status', $status);
        }

        if($created_at_from = $request->created_at_from) {
            $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') >= ?", [$created_at_from]);
        }

        if($created_at_to = $request->created_at_to) {
            $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') <= ?", [$created_at_to]);
        }

        $results = $results->paginate(13);

        $data = [
            'rows' => view('admin.commission-transactions.listrows', ['results' => $results])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
            'total' => $results->total()
        ];

        return response()->json($data, 200);
    }
}
