<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\RechargePlan;

class RechargePlansController extends Controller
{

    private $module = 'rechargeplans';
    private $moduleName = 'Recharge Plans';
    private $moduleNameSingle = 'Recharge Plan';

    public function index(Request $request)
     {
       if(!adminCan('recharge-plan','view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
          $results = RechargePlan::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
          if($name = $request->name) {
              $results->where('name', 'like', "%$name%");
          }
          if(isset($request->one_time) && is_numeric($request->one_time)){
              $results->where('one_time', $request->one_time);
          }
          if(isset($request->status) && is_numeric($request->status)){
              $results->where('status', $request->status);
          }
          if($updated_at = $request->updated_at){
              $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }
           $results = $results->paginate(16);

           $data = [
               'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'url_key'=>$this->module])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {
        $id = $request->id;
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = RechargePlan::findOrNew($id);

         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item','moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;
        if(!$id && !adminCan('recharge-plan', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('recharge-plan', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
         $this->validate($request, [
             'name' => 'required|max:255',
             'amount' => 'required',
             // 'talk_amount' => 'required',
         ]);
         if($request->talk_amount){
           $this->validate($request, [
               'from_date' => 'required|date',
               'to_date' => 'required|date|after:from_date',
           ]);
        }
         if($request->from_date || $request->to_date){
           $this->validate($request, [
               'from_date' => 'required|date',
               'to_date' => 'required|date|after:from_date',
               'talk_amount' => 'required|not_in:0|gte:amount',
           ]);
        }
        $id = $request->id;
        $item = RechargePlan::findOrNew($id);
        $item->name          = $request->name;
        $item->amount          = $request->amount?:0;
        $item->talk_amount          = $request->talk_amount?:0;
        $item->special_amount          = $request->special_amount?:0;
        $item->loyality_points          = $request->loyality_points?:0;
        $item->from_date          = $request->from_date;
        $item->to_date          = $request->to_date;
        $item->status          = $request->status?:0;
        $item->one_time          = $request->one_time?:0;
        $item->save();
        $redirectTo = route('admin.'.$this->module.'.index')  ;
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }
     public function delete(Request $request)
     {
        if(!adminCan('recharge-plan','delete')) return dd('No Permission');
        if($id = $request->id) {
            $item = RechargePlan::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request)
    {
       if(!adminCan('recharge-plan','add')) return dd('No Permission');
    if($id = $request->id) {
    $item = RechargePlan::find($id);
    $nitem = $item->replicate();
    $nitem->name = $item->name .' Cloned';
        $nitem->save();
    return response()->json(['message'=>' Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
    }
 }
