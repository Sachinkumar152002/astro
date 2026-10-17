<?php

namespace App\Http\Controllers\Admin\Pandit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{Pandit, PanditCategory, Media};

class PanditController extends Controller {
  private $module = 'Pandit';
  private $moduleName = 'Pandits';

  public function index(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $nav = 'Pandit';
    if(!adminCan($module, 'view')) return dd('No Permission');
    $breads = [
      ['name' => $moduleName]
    ];
    return view("admin.$module.list", compact('nav','breads', 'module', 'moduleName'));
  }

   public function all(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $nav = 'Pandit';
     $results = Pandit::orderBy($request->sort_by, $request->sort_dir);
     if($name = $request->name){
       $results->where('name', 'like', "%$name%");
     }
     if($email = $request->email){
       $results->where('email', 'like', "%$email%");
     }
     if($phone = $request->phone){
       $results->where('phone', 'like', "%$phone%");
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
     $nav = 'PanditAdd';
     $id = $request->id ?: 0;
     $breads = [
       ['name' => $moduleName, 'url'=> route("admin.$module.index")],
       ['name'=> ($id ? 'Edit': 'Add')]
     ];
     $item = Pandit::find($id);
     $categories = PanditCategory::pluck('name', 'id')->toArray();
     $categories = ['' => 'Select Parent'] + $categories;
     //  ksort($categories);
     $medias  = Media::where('type', $module)->get();
     return view("admin.$module.form", compact('nav', 'breads', 'item', 'categories', 'module', "moduleName", 'medias'));
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
          'slug' => "required|max:255|unique:pandits,slug,$id,id",
          'email' => "required|max:255|unique:pandits,email,$id,id",
          'phone' => "required|max:255|unique:pandits,phone,$id,id",
          'image_id' => 'required|gt:0'
      ],[
        'image_id' => 'Image is Required',
      ]);

      $item = Pandit::find($id);
      if(!$item){
        $item = new Pandit();
      }
      $item->image_id = $request->image_id;
      $item->name = $request->name;
      $item->slug = $request->slug;
      $item->email = $request->email;
      $item->phone = $request->phone;
      $item->about = $request->about;
      $item->status = $request->status ?: 0;
      $item->meta_title = $request->meta_title;
      $item->meta_keywords = $request->meta_keywords;
      $item->meta_description = $request->meta_description;
      $item->save();
      $item->order = $request->order ?: $item->id;
      $item->save();
      return response()->json(['message'=>'Saved Successfully', 'redirectto'=>route("admin.$module.edit", [$item->id])], 200);
   }

  public function delete(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $id = $request->id;
    $item = Pandit::find($id);
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
     $item = Pandit::find($id);
     if($item){
      if(!adminCan($module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $nitem = $item->replicate();
      $nitem->name = $item->name.' Cloned';
      $nitem->slug = $item->slug.'-'.$id;
      $nitem->status = 0;
      $nitem->save();
      $nitem->email = "$nitem->slug@email.com";
      $nitem->phone = "$nitem->phone$id";
      $nitem->save();
      return response()->json(['message'=>'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in cloning data'], 401);
  }
}
