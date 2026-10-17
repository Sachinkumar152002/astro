<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\PayoutPackage;

class PayoutController extends Controller
{

    private $module = 'payoutpackages';
    private $moduleName = 'Payout Packages';
    private $moduleNameSingle = 'Payout Packages';

    public function index(Request $request)
   {
      if(!adminCan('payout-package','view')) return dd('No Permission');
       $nav = $this->module;
       $breads = [
           ['name'=>$this->moduleName]
       ];
       $moduleName = $this->moduleName;
       return view('admin.payout_packages.list', compact('nav','breads', 'moduleName'));
   }

     public function all(Request $request)
     {
          $results = PayoutPackage::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');
          if($name = $request->name) {
              $results->where('name', 'like', "%$name%");
          }
          if($commission_settlement_period=$request->commission_settlement_period){
            $results->where('commission_settlement_period', $commission_settlement_period);
          }
          if($updated_at = $request->updated_at){
              $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }
           $results = $results->paginate(16);

           $data = [
               'rows' => view('admin.payout_packages.listrows',['results'=>$results, 'url_key'=>$this->module])->render(),
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
        $item  = null;
        if($id){
          $item = PayoutPackage::find($id);
        }
        return view('admin.payout_packages.form', compact('nav', 'breads', 'item','moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;
        if(!$id && !adminCan('payout-package', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('payout-package', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

       $this->validate($request, [
           'name' => 'required|max:255|unique:payout_packages,name,'.$id.',id',
           'ratio' => 'required|numeric:max:100',
       ]);
        $id = $request->id;
        $item = PayoutPackage::findOrNew($id);
        $item->name          = $request->name;
        $item->ratio          = $request->ratio?:0;
        $item->commission_settlement_period=$request->commission_settlement_period;
        $item->save();
        $redirectTo = route('admin.payoutpackages.edit', $item->id)  ;
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }
     public function delete(Request $request)
     {
        if(!adminCan('payout-package','delete')) return dd('No Permission');
        if($id = $request->id) {
            $item = PayoutPackage::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request)
    {
       if(!adminCan('payout-package','add')) return dd('No Permission');
    if($id = $request->id){
    $item = PayoutPackage::find($id);
    $nitem = $item->replicate();
    $nitem->name = $item->name .' Cloned';
        $nitem->save();
    return response()->json(['message'=>' Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
    }
}
