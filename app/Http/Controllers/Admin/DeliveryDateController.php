<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{DeliveryDate};
use App\{Product};

class DeliveryDateController extends Controller{
  private $module = 'DeliveryDate';
  private $moduleName = 'Delivery Dates';

  public function index(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $nav = $module;
    if(!adminCan($module, 'view')) return dd('No Permission');
    $breads = [
      ['name' => $moduleName]
    ];
    return view("admin.$module.list", compact('nav','breads', 'module', 'moduleName'));
  }

   public function all(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $nav = $module;
     $results = DeliveryDate::orderBy($request->sort_by, $request->sort_dir);
     if($name = $request->name){
       $results->where('name', 'like', "%$name%");
     }
     if($from = $request->from){
       $results->where('from', '>=', $from);
     }
     if($to = $request->to){
       $results->where('to', '<=', $to);
     }
    if($updated_at_from = $request->updated_at_from) {
        $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
    }
    if($updated_at_to = $request->updated_at_to) {
        $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
    }
    $results = $results->paginate(10);
    $data = [
      'rows' => view("admin.$module.listrows", ['results' => $results, "module" => $module])->render(),
      'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
    ];
    return response()->json($data, 200);
  }

   public function form(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $nav = $module;
     $id = $request->id ?: 0;
     $breads = [
       ['name' => $moduleName, 'url'=> route("admin.$module.index")],
       ['name'=> ($id ? 'Edit': 'Add')]
     ];
     $item = DeliveryDate::find($id);
     return view("admin.$module.form", compact('nav', 'breads', 'item', 'module', "moduleName"));
   }

   public function submit(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $id = $request->id ?: 0;
      if(!$id && !adminCan($module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan($module, 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $request->slug = str_slug($request->slug);
      $this->validate($request, [
          'name' => "required|max:255|unique:delivery_dates,name,$id,id",
          'from' => 'required|gt:0',
          'to' => 'required|gt:from'
      ]);

      $item = DeliveryDate::find($id);
      if(!$item){
        $item = new DeliveryDate();
      }
      $item->name = $request->name;
      $item->from = $request->from;
      $item->to = $request->to;
      $item->save();
      return response()->json(['message'=>'Saved Successfully', 'redirectto'=>route("admin.$module.edit", [$item->id])], 200);
   }

  public function delete(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $id = $request->id;
    $item = DeliveryDate::find($id);
     if($item){
       if(!adminCan($module, 'delete')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       $product = Product::where("delivery_date_id", $id)->first();
       if($product){
         return response()->json(['message'=> "Can't delete this entry. Used in product ($product->title)"], 402);
       }
       $item->delete();
       return response()->json(['message'=>'Deleted successfully!'], 200);
     }
     return response()->json(['message'=>'Error in deleting data'], 401);
   }

   public function duplicate(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $id = $request->id;
     $item = DeliveryDate::find($id);
     if($item){
      if(!adminCan($module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $nitem = $item->replicate();
      $nitem->from = $item->from;
      $nitem->to = $item->to;
      $nitem->save();
      $nitem->name = $item->name.'-'.$nitem->id;
      $nitem->save();
      return response()->json(['message'=>'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in cloning data'], 401);
  }
}
