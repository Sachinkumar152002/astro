<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\{Controller, NotificationController, PushNotificationController};
use Illuminate\Http\Request;
use App\Model\{Withdraw, Astrologer, WalletTransition};
use App\Jobs\{WithdrawNotificationJob};

class WithdrawController extends Controller
{

    private $module = 'withdraw';
    private $moduleName = 'Withdraw';
    private $moduleNameSingle = 'Withdraw';

    public function index(Request $request){
     if(!adminCan($this->module, 'view')) return dd('No Permission');
     $nav = $this->module;
     $moduleName = $this->moduleName;
       $breads = [
           ['name'=>$moduleName]
       ];
       return view('admin.'.$nav.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
       $nav = $this->module;
        $results = Withdraw::orderBy('updated_at', 'DESC');

        if($astrologer = $request->astrologer){
          $astrologersId = Astrologer::where('display_name', 'like', "%$astrologer%")->pluck('id')->toArray();
          $results->whereIn('user_id', $astrologersId)
            ->where('user_type', 'astrologer');
        }
        if($phone = $request->phone){
          $astrologersId = Astrologer::where('phone', 'like', "%$phone%")->pluck('id')->toArray();
          $results->whereIn('user_id', $astrologersId)
            ->where('user_type', 'astrologer');
        }
        if($status = $request->status) {
          $results->where('status', $status);
        }
        if($updated_at_from = $request->updated_at_from) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
        }
        if($updated_at_to = $request->updated_at_to) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
        }
       $results = $results->paginate(16);
       $data = [
           'rows' => view('admin.'.$nav.'.listrows',['results'=>$results, 'nav' => $nav])->render(),
           'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
       ];
       return response()->json($data, 200);
     }

     public function form(Request $request){
       $nav = $this->module;
       $moduleName = $this->moduleName;
       $id = $request->id;
       $breads = [
         ['name'=>$moduleName, 'url'=> route('admin.'.$nav.'.index')],
         ['name'=>($id ? 'Edit': 'Add')]
       ];
       $astrologer_id = $request->astrologer_id ?? 0;
       $astrologer = Astrologer::find($astrologer_id);
       $item = Withdraw::find($id);
       if($item){
         $astrologer_id = $item->user_id;
         $astrologer = Astrologer::find($item->user_id);
       }
       $withdraws = Withdraw::where('user_id', $astrologer->id)->where('user_type', 'astrologer')->get();
       $withdrawedAmount = $withdraws->where('status', 'done')->sum('amount');
       $totalAmount = $astrologer->wallet;
       $availableAmount = $astrologer->wallet;
       if($availableAmount < 0){
         $availableAmount = 0;
       }
       return view('admin.'.$nav.'.form', compact('nav', 'breads', 'item','moduleName', 'totalAmount', 'withdrawedAmount', 'availableAmount', 'astrologer'));
     }

     public function submit(Request $request){
       $nav = $this->module;
       $moduleName = $this->moduleName;
       $id = $request->id;
       if(!$id && !adminCan($this->module, 'add')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       if($id && !adminCan($this->module, 'update')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       $astrologer_id = $request->user_id ?? 0;
       $astrologer = Astrologer::find($astrologer_id);

       $withdraws = Withdraw::where('user_id', $request->user_id)->where('user_type', 'astrologer')->get();
       $withdrawedAmount = $withdraws->where('status', 'done')->sum('amount');

       $totalAmount = $astrologer->wallet + $withdrawedAmount;
       $availableAmount = $astrologer->wallet;
       if($availableAmount < 0){
         $availableAmount = 0;
       }

       $this->validate($request, [
         // 'amount' => 'required|numeric|gt:0|lte:'.$availableAmount,
         'amount' => 'required|numeric|gt:0',
         'status' => 'required',
         'description' => 'required',
       ]);

        $item = Withdraw::find($id);
        if(!$item){
          $item = new Withdraw();
          $item->user_id = $request->user_id;
          $item->user_type = 'astrologer';
          $item->amount = $request->amount;
        }
        $item->status = $request->status;
        $item->description = $request->description;
        $item->save();
        $astrologer = Astrologer::find($item->user_id);
        $message = "";
        if($item->status == 'done'){
          $message = "Your withdraw request has been accepted of Rs. $item->amount";
          $walletTransition = WalletTransition::where('user_id', $astrologer->id)->where('user_type', 'astrologer')->where('table_id', $item->id)->where('table_name', 'withdraws')->first();
          if(!$walletTransition){
            $walletTransition = new WalletTransition();
            $walletTransition->user_id = $astrologer->id;
            $walletTransition->user_type = "astrologer";
            $walletTransition->table_id = $item->id;
            $walletTransition->table_name = "withdraws";
            $walletTransition->action = 0; // debit
            $walletTransition->status = 1; // success
            $walletTransition->remark = "Accepted Your Withdraw Request";
            $walletTransition->invoice = 0;
          }
          $walletTransition->amount = $item->amount;
          $walletTransition->save();

          // send done mail
          // astrologer
          WithdrawNotificationJob::dispatch($item, $astrologer->display_name, $astrologer->email, null, $astrologer, 'astrologer', 'done');

          // admin
          WithdrawNotificationJob::dispatch($item, 'Admin', config('vrdcms.admin_email'), null, $astrologer, 'admin', 'done');
        }elseif($item->status == 'cancel'){
          $message = "Your withdraw request has been cancelled of Rs. $item->amount";
          // credit back to astrologer wallet
          $astrologer->wallet += $item->amount;
          $astrologer->save();

          // send cancel mail
          // astrologer
          WithdrawNotificationJob::dispatch($item, $astrologer->display_name, $astrologer->email, null, $astrologer, 'astrologer', 'cancel');

          // admin
          WithdrawNotificationJob::dispatch($item, 'Admin', config('vrdcms.admin_email'), null, $astrologer, 'admin', 'cancel');
        }

        if($message){
          // normal notification
          $notification['astrologer_id'] = $astrologer->id;
          $notification['user_id'] = 0;
          $notification['message'] = $message;
          $notification['image_id'] = 0;
          $notification['link'] = route('astrologer.withdraws');
          $notification['app_link'] = "payout_histories";
          $notification['for'] = 2;
          $notification['on'] = 0;
          $notification['status'] = 1;
          $notificationController = new NotificationController();
          $notificationController->create($notification);

          // push notification
          $title="Withdraw Requested";
          $body=$notification['message'];
          $dataArray = [
            'type' => $notification['app_link']
          ];
          $pushNotificationController = new PushNotificationController();
          $pushNotificationController->sendNotification($astrologer->fcm_token, $title, $body, $dataArray);
        }

        $redirectTo = route('admin.'.$nav.'.edit', [$item->id]);
        //return response
        return response()->json(['message'=> 'Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request){
       $nav = $this->module;
       $id = $request->id;
       if(!adminCan($this->module, 'delete')){
         return response()->json(['message'=>'No Permission'], 402);
       }
      if($id){
        $item = Withdraw::find($id);
        $item->delete();
        return response()->json(['message' => 'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
     }
}
