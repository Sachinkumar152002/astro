<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Vastu;
use App\Model\Media;
use App\Model\VastuServicePackage;
use App\Model\Category;
use App\Model\Article;
use App\Model\VastuServicePackageName;

class VastuController extends Controller
{
    private $module = 'vastu';
    private $moduleName = 'Vastu';
    private $moduleNameSingle = 'Vastu';

    public function index(Request $request)
     {
        if(!adminCan('vastu-page','view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>'Vastu Detail']
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }
     public function main(Request $request)
      {
         if(!adminCan('vastu-page','view')) return dd('No Permission');
          $nav = $this->module;
          $breads = [
              ['name'=>'Vastu Detail']
          ];
          $moduleName = $this->moduleName;
          return view('admin.vastu_main.list', compact('nav','breads', 'moduleName'));
      }

     public function allMain(Request $request)
     {
        $results = Vastu::where('type', 'page');
         $results = $results->paginate(1);

         $data = [
             'rows' => view('admin.vastu_main.listrows',['results'=>$results])->render(),
             'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
         ];
         return response()->json($data, 200);
     }

     public function all(Request $request)
     {
        $results = Vastu::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc')->where('type', 'sub-page');

        if($name = $request->name) {
            $results->where('name', 'like', "%$name%");
        }
        if(is_numeric($request->status)) {
          $status = $request->status;
          $results->where('status', "$status");
        }
         if($updated_at_from = $request->updated_at_from) {
             $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
         }
         if($updated_at_to = $request->updated_at_to) {
             $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
         }
         $results = $results->paginate(13);

         $data = [
             'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results])->render(),
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
         $item = Vastu::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();
         if(in_array($item->type, ['page'])) {
            $nav .= '_page';
         }

         // $category_ids = Article::where('status', 1)->groupBy('category_id')->pluck('category_id')->toArray();
         // $categories = Category::where('status', 1)->whereIn('id', $category_ids)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
         $categories = Category::selectTreeArray2();
         $categories[0] = 'Select';
         ksort($categories);

         // return $categories;
         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item', 'medias', 'moduleName', 'categories'));
     }

     public function submit(Request $request) {
       // dd($request->all());

        $id = $request->id;
        if(!$id && !adminCan('vastu-page', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('vastu-page', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

         $this->validate($request, [
             'name' => 'required|max:255|unique:vastus,name,'.$id.',id',
             'slug' => 'required|max:255|unique:vastus,slug,'.$id.',id',
             'image_id' => 'required|numeric|not_in:0',
             'banner_image_id' => 'required|numeric|not_in:0',
             'short_description' => 'required',
             'description' => 'required',
         ],[
             'name' => 'Name is Required',
             'image_id' => 'Image is Required',
             'banner_image_id' => 'Banner Image is Required',
             'short_description' => 'Short Description is Required',
             'description' => 'Description is Required',
         ]);

         $id = $request->id;

        $item = Vastu::findOrNew($id);
        $item->image_id 		= $request->image_id?:0;
        $item->banner_image_id 		= $request->banner_image_id?:0;
        $item->status 		= $request->status?:0;
        $item->name             = $request->name;
        $item->slug             = $request->slug ? str_slug($request->slug) : str_slug($request->name);
        $item->short_description          = $request->short_description;
        $item->blog_category_id          = $request->blog_category_id?:0;
        $item->description          = $request->description;
        $item->meta_keywords          = $request->meta_keywords;
        $item->meta_title          = $request->meta_title;
        $item->meta_description          = $request->meta_description;
        $item->why_choose_us          = $request->why_choose_us;
        $item->benifits          = $request->benifits;
        $item->link          = $request->link;
        $item->show_on_detail 		= $request->show_on_detail?:0;
        $item->save();
        // $item->package_heading          = $request->package_heading;
        // $item->package_title          = $request->package_title;
        if($item->id != 1){
            $item->type = 'sub-page';
        }
        // if($item->type == 'sub-page'){
        //     $item->slug             = str_slug($request->name);
        // }
        $item->save();
        // dd($request->all());
        // if($request->services && count($request->services)){
        //   VastuServicePackage::where('service_id', $item->id)->delete();
        //   foreach ($request->services as $key => $serviceItem) {
        //     $service = new VastuServicePackage;
        //     $service->service_id = $item->id;
        //     $service->service = $serviceItem;
        //     $service->save();
        //   }
        // }
        $redirectTo =route('admin.vastu.edit', [$item->id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan('vastu-page','delete')) return dd('No Permission');
        if($id = $request->id) {
            $item = Vastu::find($id);
            $item->delete();
            VastuServicePackage::where('service_id', $id)->delete();
            VastuServicePackageName::where('service_id', $id)->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request)
      {
        if(!adminCan('vastu-page','add')) return dd('No Permission');
    if($id = $request->id) {
    $item = Vastu::find($id);
    $vItem = $item->replicate();
          $vItem->save();
    $vItem->name = $item->name.' Cloned'.'-'.$vItem->id;
          $vItem->save();
          $vItem->slug = str_slug($vItem->name);
          $vItem->save();
    $services = VastuServicePackage::where('service_id', $id)->get();
          foreach ($services as $key => $sItem) {
            $s = $sItem->replicate();
            $s->service_id = $vItem->id;
            $s->save();
            $s->heading = $sItem->heading.' Cloned'.'-'.$s->id;
            $s->save();
            $vSpnItems = VastuServicePackageName::where('package_id', $sItem->id)->get();
            foreach ($vSpnItems as $key => $vSpnItem) {
              $vSpn = $vSpnItem->replicate();
              $vSpn->service_id = $vItem->id;
              $vSpn->package_id = $s->id;
              $vSpn->save();
            }
          }
    return response()->json(['message'=>' Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
      }
 }
