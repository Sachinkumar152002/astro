<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{Vendor};
use DB;

class VendorController extends Controller
{
  private $module = 'vendors';
  private $moduleName = 'Vendors';

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
     $results = Vendor::orderBy($request->sort_by, $request->sort_dir);
     if($name = $request->name){
       $results->where('name', 'like', "%$name%");
     }
     if($email = $request->email){
       $results->where('email', 'like', "%$email%");
     }
     if($phone = $request->phone){
       $results->where('phone', 'like', "%$phone%");
     }
     if($business_name = $request->business_name){
       $results->where('business_name', 'like', "%$business_name%");
     }
     $status = $request->status;
     if($request->has("status") && is_numeric($status)){
       $results->where('status', $status);
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
     $nav = $module."add";
     $id = $request->id ?: 0;
     $breads = [
       ['name' => $moduleName, 'url'=> route("admin.$module.index")],
       ['name'=> ($id ? 'Edit': 'Add')]
     ];
     $item = Vendor::find($id);
     $countries = DB::table("master_countries")->get();
     $states = [];
     $cities = [];
     if($item){
       $states = DB::table("master_states")->where("country_id", $item->country_id)->get();
       $cities = DB::table("master_cities")->where("state_id", $item->state_id)->get();
     }
     return view("admin.$module.form", compact('nav', 'breads', 'item', 'module', "moduleName", 'countries', "states", "cities"));
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
        'name' => 'required',
        'email' => "required|max:255|unique:vendors,email,$id,id",
        'phone' => "required|max:255|unique:vendors,phone,$id,id",
        'business_name' => 'required'
      ]);
      // dd($request->all());
      $item = Vendor::find($id);
      if(!$item){
        $item = new Vendor();
      }
      $item->name = $request->name;
      $item->phone = $request->phone;
      $item->email = $request->email;
      $item->address = $request->address ?: "";
      $item->pincode = $request->pincode ?: "";
      $country_id = $request->country_id ?: 0;
      if($country_id){
        $item->country_id = $country_id;
        $country = DB::table("master_countries")->where("id", $country_id)->first();
        $item->country = $country ? $country->name : $country_id;
      }else{
        $item->country_id = $country_id;
        $item->country = $country_id;
      }
      $state_id = $request->state_id ?: 0;
      if($state_id){
        $item->state_id = $state_id;
        $state = DB::table("master_states")->where("id", $state_id)->first();
        $item->state = $state ? $state->name : $state_id;
      }else{
        $item->state_id = $state_id;
        $item->state = $state_id;
      }
      $city_id = $request->city_id ?: 0;
      if($city_id){
        $item->city_id = $city_id;
        $city = DB::table("master_cities")->where("id", $city_id)->first();
        $item->city = $city ? $city->name : $city_id;
      }else{
        $item->city_id = $city_id;
        $item->city = $city_id;
      }
      $item->business_name = $request->business_name ?: "";
      $item->pan_number = $request->pan_number ?: "";
      $item->gst_number = $request->gst_number ?: "";
      $item->status = $request->status ?: 0;
      $item->save();
      return response()->json(['message'=>'Saved Successfully', 'redirectto'=>route("admin.$module.edit", [$item->id])], 200);
   }

  public function delete(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $id = $request->id;
    $item = Vendor::find($id);
     if($item){
       if(!adminCan($module, 'delete')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
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
     $item = Vendor::find($id);
     if($item){
      if(!adminCan($module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $nitem = $item->replicate();
      $nitem->name = $item->name.' Cloned';
      $nitem->email = "";
      $nitem->phone = "";
      $nitem->business_name = "";
      $nitem->status = 0;
      $nitem->save();
      return response()->json(['message'=>'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in cloning data'], 401);
  }
}
