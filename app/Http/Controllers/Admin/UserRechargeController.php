<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Invoice;
use App\Model\UserRechargeHistory;

class UserRechargeController extends Controller
{
    public function index(Request $request)
    {
      if(!adminCan('user-recharge', 'view')) return dd('No Permission');
    $nav = 'user_recharge';
        $breads = [
            ['name'=>'Customer Recharges']
        ];
        return view('admin.user_recharge.list', compact('nav','breads'));
    }

    public function all(Request $request){
      $query = UserRechargeHistory::orderBy('updated_at','desc');
      if($my_txn_id = $request->my_txn_id){
        $query->where('my_txn_id', 'like', "%$my_txn_id%");
      }
      if($invoice_number = $request->invoice_number){
       $invoices_ids = Invoice::whereRaw('CONCAT(prefix, number) like ?', ["%$invoice_number%"])->where('relation_type', 'recharge')->pluck('relation_id')->toArray();
       $query->whereIn('id', $invoices_ids);
      }
      if($payment_status = $request->payment_status){
        $query->where('payment_status', $payment_status);
      }
      if($updated_at_from = $request->updated_at_from){
        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }
      if($updated_at_to = $request->updated_at_to){
        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }
      $query = $query->paginate(10);

      $data = [
          'rows' => view('admin.user_recharge.listrows',['results'=>$query])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$query])->render(),
      ];
      return response()->json($data, 200);
    }

    public function form(Request $request)
    {
        $nav = 'user_recharge'; // Navigation identifier
        $id = $request->id; // Course ID (for edit)
        $breads = [
            ['name' => 'Customer Recharges', 'url' => route('admin.user_recharge')],
            ['name' => ($id ? 'Edit' : 'Add')]
        ];

        $data = $id ? UserRechargeHistory::find($id) : false;
        return view('admin.user_recharge.form', compact('nav', 'breads', 'data'));
    }

    public function delete(Request $request)
    {
      if(!adminCan('user-recharge', 'delete')) return dd('No Permission');
      if($id = $request->id) {
        $data = UserRechargeHistory::find($order_id);
        $data->delete();
        return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }
}
