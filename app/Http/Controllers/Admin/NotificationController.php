<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Notification;
use App\User;
use App\Model\Astrologer;
use App\Jobs\{SendPushNotificationJob};

class NotificationController extends Controller
{
  private $module = 'notification';
  private $moduleName = 'Notification';
  private $moduleNameSingle = 'Notification';

    public function index(Request $request){
       if(!adminCan($this->module,'view')) return dd('No Permission');
         $nav = $this->module;
         $user_id = $request->user_id?:0;
         $astrologer_id = $request->astrologer_id?:0;
         $breads = [];
         if($user_id){
           $breads[] = ['name'=> 'Customers', 'url'=> route('admin.customer.index')];
         }elseif($astrologer_id){
           $astrologer = Astrologer::find($astrologer_id);
           $breads[] = ['name'=> $astrologer->type, 'url'=> route('admin.'.$astrologer->type.'.index')];
         }
         $breads[] = ['name'=>$this->moduleName];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName', 'user_id', 'astrologer_id'));
     }

     public function all(Request $request){
      $nav = $this->module;
      $user_id = $request->user_id?:0;
      $astrologer_id = $request->astrologer_id?:0;
      $query = Notification::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
      if($user = $request->user){
        $users = User::where('name','like', "%$user%")->pluck('id')->toArray();
        // dd($users);
        if(count($users)){
          $query->whereIn('user_id', $users);
        }
        $astrologers = Astrologer::where('display_name','like', "%$user%")->pluck('id')->toArray();
        if(count($astrologers)){
          $query->orWhereIn('astrologer_id', $astrologers);
        }
      }elseif($user_id){
        $query->where('user_id', $user_id);
      }elseif($astrologer_id){
        $query->where('astrologer_id', $astrologer_id);
      }
      if($request->has('for') && is_numeric($request->for)) {
        $query->where('for', $request->for);
      }
      // if($request->has('view') && is_numeric($request->view)) {
      //   $query->where('view', $request->view);
      // }
      if($request->has('on') && is_numeric($request->on)) {
        $query->where('on', $request->on);
      }
      if($request->has('status') && is_numeric($request->status)) {
        $query->where('status', $request->status);
      }
       if($updated_at_from = $request->updated_at_from) {
          $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to) {
         $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
       $results = $query->paginate(16);
       $data = [
           'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'nav'=>$nav])->render(),
           'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
       ];
      return response()->json($data, 200);
     }

     public function form(Request $request){
      $id = $request->id;
      $nav = $this->module;
      $user_id = $request->user_id?:0;
      $astrologer_id = $request->astrologer_id?:0;
      $moduleName = $this->moduleNameSingle;
      $breads = [];
      if($user_id){
        $breads[] = ['name'=> 'Customers', 'url'=> route('admin.customer.index')];
      }elseif($astrologer_id){
        $astrologer = Astrologer::find($astrologer_id);
        $breads[] = ['name'=> $astrologer->type, 'url'=> route('admin.'.$astrologer->type.'.index')];
      }
      $breads[] = ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index', [$user_id, $astrologer_id])];
      $breads[] = ['name'=>($id ? 'Edit': 'Add')];
       $item = Notification::find($id);
       if(!$item){
         $item=null;
       }else{
         $user_id = $item->user_id;
         $astrologer_id = $item->astrologer_id;
       }
       // return $item;
       $user = null;
       $astrologer = null;
       if($user_id){
         $user = User::find($user_id);
       }
       if($astrologer_id){
         $astrologer = Astrologer::find($astrologer_id);
       }
       return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item','moduleName', 'user', 'astrologer', 'user_id', 'astrologer_id'));
     }

     public function submit(Request $request) {
        $id = $request->id ?: 0;
        if(!$id && !adminCan($this->module, 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan($this->module, 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        $this->validate($request, [
          'message' => 'required|max:255',
          'for' => 'required'
        ]);
        $user_id = $request->user_id?:0;
        $astrologer_id = $request->astrologer_id?:0;

        $item = Notification::findOrNew($id);
        $item->user_id = $user_id;
        $item->astrologer_id = $astrologer_id;
        $item->message = $request->message;
        $item->link = $request->link ?: '';
        // $item->for   = $user_id ? 1 : ( $astrologer_id ? 2 : $request->for );
        $item->for  = $request->for;
        $item->on = $request->on ?: 0;
        $item->status=$request->status ?: 0;
        $item->save();

        if($id == 0 && $item->on == 0 && $item->status == 1){
          SendPushNotificationJob::dispatch($item);
        }

        $redirectTo = route('admin.'.$this->module.'.edit', [$item->id, $user_id, $astrologer_id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request){
       if(!adminCan($this->module,'delete')){
         return response()->json(['message'=>'No Permission'], 401);
       }
        if($id = $request->id){
          $item = Notification::find($id);
          $item->delete();
          return response()->json(['message'=>'Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

      public function duplicate(Request $request){
        if(!adminCan($this->module,'add')) return dd('No Permission');
        if($id = $request->id) {
          $user_id = $request->user_id?:0;
          $astrologer_id = $request->astrologer_id?:0;
          $item = Notification::find($id);
          $nitem = $item->replicate();
          $nitem->user_id = $user_id;
          $nitem->astrologer_id = $astrologer_id;
          $nitem->save();
          return response()->json(['message'=>'Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in Cloning data'], 401);
      }
}
