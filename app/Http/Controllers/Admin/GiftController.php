<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{Gift};

class GiftController extends Controller
{

    private $module = 'gift';
    private $moduleName = 'Gift';
    private $moduleNameSingle = 'Gift';

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
          $results = Gift::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

          if($name = $request->name) {
              $results->where('name', 'like', "%$name%");
          }
           if($price_from = $request->price_from) {
               $results->where('price', '>=', $price_from);
           }
            if($price_to = $request->price_to) {
                $results->where('price', '<=', $price_to);
            }
           if($updated_at_from = $request->updated_at_from) {
               $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
           }
           if($updated_at_to = $request->updated_at_to) {
               $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
           }
           $results = $results->paginate(16);

           $data = [
               'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'module' => $this->module])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {
       $id = $request->id;
        // $nav = $this->module;
        $nav = "giftadd";
        $module = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = Gift::findOrNew($id);
         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item', 'moduleName', "module"));
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
             'name' => 'required|unique:gifts,name,'.$id.',id',
             'price' => 'required|numeric|gt:0',
         ]);
         // dd($request->all());
        $id = $request->id;
        $item = Gift::findOrNew($id);
        $item->name          = $request->name;
        $item->icon_id          = $request->icon_id;
        $item->price          = $request->price;
        $item->status          = $request->status?:0;
        $item->save();
        $redirectTo = route('admin.'.$this->module.'.edit', [$item->id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
       if(!adminCan($this->module, 'delete')){
         return response()->json(['message'=>'No Permission'], 402);
       }
        if($id = $request->id) {
            $item = Gift::find($id);
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
          $item = Gift::find($id);
          $nitem = $item->replicate();
          $nitem->name = $nitem->name.' Cloned';
          $nitem->save();
          return response()->json(['message'=>'Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in Cloning data'], 401);
      }
}
