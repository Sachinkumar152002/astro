<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{Coupon,RechargePlan,UserRechargeHistory,AdminSideCartItem};
use App\{User,Order,OrderItem,Product};

class CouponController extends Controller
{

    private $module = 'coupons';
    private $moduleName = 'Coupons';
    private $moduleNameSingle = 'Coupon';

    public function index(Request $request){
       if(!adminCan($this->module, 'view')) return dd('No Permission');
       $nav = $this->module;
       $breads = [
           ['name'=>$this->moduleName]
       ];
       $moduleName = $this->moduleName;
       return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
    }

     public function all(Request $request)
     {
          $results = Coupon::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

          if($name = $request->name) {
              $results->where('name', 'like', "%$name%");
          }
          if($code = $request->code) {
            $results->where('code', 'like', "%$code%");
          }
          if(isset($request->fixed_perc) && is_numeric($request->fixed_perc)){
            $results->where('fixed_perc', $request->fixed_perc);
          }
          if($apply_on = $request->apply_on){
            $results->where('apply_on', $apply_on);
          }
          if(isset($request->status) && is_numeric($request->status)) {
            $results->where('status', $request->status);
          }
          if($how_many_time = $request->how_many_time){
            if($how_many_time=='one_time'){
              $results->where('one_time', 1);
            }elseif($how_many_time=='only_one_time'){
              $results->where('only_one_time', 1);
            }else{
              $results->where('one_time', 0)->where('only_one_time', 0);
            }
          }
          if(isset($request->only_one_time) && is_numeric($request->only_one_time)) {
            $results->where('only_one_time', $request->only_one_time);
          }
          if($updated_at = $request->updated_at) {
              $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }
           $results = $results->paginate(16);

           $data = [
               'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'url_key' => $this->module])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {
       $id = $request->id;
        // $nav = $this->module;
        $nav = "couponsadd";
        $module = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = Coupon::findOrNew($id);

         $rechargePlans = RechargePlan::get();
         $couponCondtions = $item->conditions ? json_decode($item->conditions, true) : [];
         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item','moduleName', 'rechargePlans', 'couponCondtions', "module"));
     }

     public function submit(Request $request) {

        $id = $request->id;
        if(!$id && !adminCan($this->module, 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan($this->module, 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
         $this->validate($request, [
             'name' => 'required|max:255',
             'code' => 'required|unique:coupons,code,'.$id.',id',
             'amount' => 'required',
             'minimum_amount' => 'required',
             'fixed_perc' => 'required',
             'description' => 'required',
             'apply_on' => 'required',
         ],[
             'fixed_perc.required' => 'Fixed or Percentage is required',
             'minimum_amount.required' => 'Minimum Amount is required',
             'apply_on.required' => 'Apply on is required',
         ]);

         if($request->from_date || $request->to_date){
          $this->validate($request, [
              'from_date' => 'required|date',
              'to_date' => 'required|date|after:from_date',
          ],[
              'from_date.required' => 'From date is required',
              'to_date.required' => 'To date is required',
          ]);
         }

         if($request->fixed_perc == 0){
          $this->validate($request, [
              'minimum_amount' => 'gt:amount',
              'amount' => 'lt:minimum_amount',
          ]);
         }
         // return 'user';
        $id = $request->id;
        $item = Coupon::findOrNew($id);
        $item->name          = $request->name;
        $item->code          = $request->code;
        $item->description   = $request->description;
        $item->amount        = $request->amount?:0;
        $item->minimum_amount        = $request->minimum_amount?:0;
        $item->fixed_perc          = $request->fixed_perc?:0;
        $item->status          = $request->status?:0;
        $item->from_date          = $request->from_date;
        $item->to_date          = $request->to_date;
        // $item->conditions          = $request->conditions? json_encode($request->conditions) : null;
        $item->apply_on          = $request->apply_on;
        $item->one_time          = $request->how_many_time && $request->how_many_time == 'one_time'?1:0;
        $item->only_one_time          = $request->how_many_time && $request->how_many_time == 'only_one_time'?1:0;
        // $item->one_time          = $request->one_time?:0;
        // $item->only_one_time          = $request->only_one_time?:0;
        $item->save();
        // dd($request->one_time);
        $redirectTo = route('admin.'.$this->module.'.edit', [$item->id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
       if(!adminCan($this->module, 'delete')){
         return response()->json(['message'=>'No Permission'], 402);
       }
        if($id = $request->id) {
            $item = Coupon::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }


      public function duplicate(Request $request)
      {
        if(!adminCan($this->module, 'add')){
          return response()->json(['message'=>'No Permission'], 402);
        }
        if($id = $request->id) {
          $item = Coupon::find($id);
          $nitem = $item->replicate();
          $nitem->name = $nitem->name.' Cloned';
          $nitem->save();
          return response()->json(['message'=>'Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in Cloning data'], 401);
      }

      public function apply(Request $request){
        $request->validate([
          'coupon_code'=>'required',
          'type'=>'required',
        ]);
        $user=User::find($request->user_id);
        if(!$user){
          return response()->json(['success'=>false,'message'=>'Invalid action.'],402);
        }
        $coupon_code=trim($request->coupon_code);
        $type=$request->type;
        $plan_id=$request->plan_id?:0;
        $user_type=$request->user_type?:"user";
        $amount=0;
        $couponAmount=0;
        $currentDate = date('Y-m-d');
        $coupon = Coupon::where('code', $coupon_code)
        ->where('status', 1)
        ->where(function($query) use ($currentDate){
          $query->whereNull('from_date')
          ->orWhere(function($q) use ($currentDate){
            $q->whereDate('from_date', '<=', $currentDate)
            ->whereDate('to_date', '>=', $currentDate);
          });
        })
        ->first();
        if(!$coupon){
          return response()->json(['success'=>false,'message'=>'Invalid coupon.'],402);
        }
        if($coupon->only_one_time){
          if($type == 'order'){
            $order = Order::whereNotIn('order_status', ['pending', 'cancel'])
            ->where('coupon_id', $coupon->id)
            ->where('coupon', $coupon->code)
            ->first();
            if($order){
              // return response()->json(['success'=>false,'message'=>'Invalid coupon.'],402);
              return response()->json(['success'=>false,'message'=>'This coupon is valid only one time.'],402);
            }
          }elseif($type == 'recharge'){
            $userRecharge = UserRechargeHistory::where('payment_status', 'success')
            ->where('coupon_id', $coupon->id)
            ->where('coupon_code', $coupon->code)
            ->first();
            if($userRecharge){
              // return response()->json(['success'=>false,'message'=>'Invalid coupon.'],402);
              return response()->json(['success'=>false,'message'=>'This coupon is valid only one time.'], 402);
            }
          }
        }
        if($coupon->one_time){
          if($type == 'order'){
            $order = Order::where('status', 'Success')
            ->where('user_id', $user->id)
            ->where('user_type', $user_type)
            ->where('coupon_id', $coupon->id)
            ->where('coupon', $coupon->code)
            ->first();
            if($order){
              return response()->json(['success' => false,'message'=>'This coupon is valid only one time and you already used this coupon once.'],402);
            }
          }elseif($type == 'recharge'){
            $userRecharge = UserRechargeHistory::where('payment_status', 'success')
            ->where('user_id', $user->id)
            ->where('user_type', $user_type)
            ->where('coupon_id', $coupon->id)
            ->where('coupon_code', $coupon->code)
            ->first();
            if($userRecharge){
              return response()->json(['success'=>false,'message'=>'This coupon is valid only one time and you already used this coupon once.'], 402);
            }
          }
        }
        if($type == 'order'){
          if($coupon->apply_on == 'recharge'){
            return response()->json(['success'=>false,'message'=>'Not valid for shopping.'],402);
          }
          if($coupon->apply_on == 'any'){
            $adminSideCartItems=AdminSideCartItem::where('user_id',$user->id)
            ->where('user_type',$user_type)
            ->get();
            if($adminSideCartItems->count() == 0){
              return response()->json(['success'=>false,'message'=>'Please Add item in cart first.'],402);
            }
            $amount=$adminSideCartItems->sum("unit_sale_price");
          }elseif($coupon->apply_on == 'product'){
            $adminSideCartItems=AdminSideCartItem::where('user_id',$user->id)
            ->where('user_type',$user_type)
            ->where('item_type',"product")
            ->get();
            if($adminSideCartItems->count() == 0){
              return response()->json(['success'=>false,'message'=>'Please Add item in cart first.'],402);
            }
            $amount=$adminSideCartItems->sum("unit_sale_price");
          }
        }elseif($type == 'recharge'){
          if($coupon->apply_on == 'product'){
            return response()->json(['success'=>false,'message'=>'Not valid for recharge.'], 402);
          }
          $plan=RechargePlan::find($plan_id);
          if(!$plan){
            return response()->json(['success'=>false,'message'=>'Item not found'],402);
          }
          $amount=$plan->amount;
        }
        if($amount<$coupon->minimum_amount){
          return response()->json(['success'=>false,'message'=>'Minimum amount to use this coupon is ₹'.$coupon->minimum_amount
          ], 402);
        }
        if($coupon->fixed_perc){
          $percentage=$coupon->amount;
          $couponAmount=($amount/100)*$percentage;
        }else{
          $couponAmount=$coupon->amount;
        }
        return response()->json(['success'=>true,'message'=>'Coupon code applied.','coupon_id'=>$coupon->id,'coupon_code'=>$coupon->code,'coupon_amount'=>$couponAmount,"coupon_apply_on"=>$coupon->apply_on],200);
      }
 }
