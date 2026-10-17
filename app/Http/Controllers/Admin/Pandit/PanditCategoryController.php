<?php

namespace App\Http\Controllers\Admin\Pandit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{PanditCategory, Media};

class PanditCategoryController extends Controller {
  private $module = 'PanditCategory';
  private $moduleName = 'Pandit Categories';

  public function index(Request $request){
    $module = $this->module;
    $moduleName = $this->moduleName;
    $nav = 'PanditCategory';
    if(!adminCan($module, 'view')) return dd('No Permission');
    $breads = [
      ['name' => $moduleName]
    ];
    return view("admin.$module.list", compact('nav','breads', 'module', 'moduleName'));
  }

   public function all(Request $request){
     $module = $this->module;
     $moduleName = $this->moduleName;
     $nav = 'PanditCategory';
     $results = PanditCategory::orderBy($request->sort_by, $request->sort_dir);
     if($parent_name = $request->parent_name) {
       $categoriesId = PanditCategory::where('name', 'like', "%$parent_name%")->pluck("id")->toArray();
       $results->whereIn("parent_id", $categoriesId);
     }
     if($name = $request->name){
       $results->where('name', 'like', "%$name%");
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
     $nav = 'PanditCategoryAdd';
     $id = $request->id ?: 0;
     $breads = [
       ['name' => $moduleName, 'url'=> route("admin.$module.index")],
       ['name'=> ($id ? 'Edit': 'Add')]
     ];
     $item = PanditCategory::find($id);
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
          'name' => "required|max:255|unique:pandit_categories,name,$id,id",
          'slug' => "required|max:255|unique:pandit_categories,slug,$id,id",
          'image_id' => 'required|gt:0'
      ],[
        'image_id' => 'Image is Required',
      ]);

      $item = PanditCategory::find($id);
      if(!$item){
        $item = new PanditCategory();
      }
      $item->parent_id = $request->parent_id ?: 0;
      $item->image_id = $request->image_id;
      $item->name = $request->name;
      $item->slug = $request->slug;
      $item->description = $request->description;
      $item->show_in_navigation = $request->show_in_navigation ?: 0;
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
    $item = PanditCategory::find($id);
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
     $item = PanditCategory::find($id);
     if($item){
      if(!adminCan($module, 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $nitem = $item->replicate();
      $nitem->name = $item->name.' Cloned';
      $nitem->slug = $item->slug.'-'.$id;
      $nitem->status = 0;
      $nitem->save();
      return response()->json(['message'=>'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in cloning data'], 401);
  }
}
