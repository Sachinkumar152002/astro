<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Media;
use App\Model\ModulePackage;
use App\Model\ModuleFeature;
use App\Model\NgSoftware;
use App\Model\Category;
use App\Model\Article;

class NgSoftwareController extends Controller
{
    private $module = 'software';
    private $moduleName = 'Ng Software';
    private $moduleNameSingle = 'Ng Software';

    public function index(Request $request)
     {
        if(!adminCan('cms-two','view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>'Cms2']
         ];
         $moduleName = 'Cms2';
         return view('admin.software.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
      $results = NgSoftware::paginate(13);

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
             ['name'=>'Cms2', 'url'=> route('admin.software.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = NgSoftware::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();

         // $category_ids = Article::where('status', 1)->groupBy('category_id')->pluck('category_id')->toArray();
         // $categories = Category::where('status', 1)->where('parent_id', 0)->whereIn('id', $category_ids)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
         $categories = Category::selectTreeArray2();
         $categories[0] = 'Select';
         ksort($categories);

         return view('admin.software.form', compact('nav', 'breads', 'item', 'medias', 'moduleName','categories'));
     }

     public function submit(Request $request) {

        $id = $request->id;
        if(!$id && !adminCan('cms-two', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('cms-two', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

         $this->validate($request, [
             // 'title' => 'required|max:255|unique:ng_softwares,title,'.$id.',id',
             'title' => 'required',
             'page' => 'required|max:255|unique:ng_softwares,page,'.$id.',id',
             'slug' => 'required|max:255|unique:ng_softwares,slug,'.$id.',id',
             'short_description' => 'required',
             'description' => 'required',
             'image_id' => 'required',
             'banner_image_id' => 'required',
         ],[
           'title' => 'title is Required',
           'page.required' => 'Page is Required',
           'short_description' => 'Short Description is Required',
           'description' => 'Description is Required',
           'image' => 'Image is Required',
           'banner_image_id' => 'Banner is Required',
         ]);

         $id = $request->id;

        $item = NgSoftware::findOrNew($id);
        $item->image_id 		= $request->image_id?:0;
        $item->banner_image_id 		= $request->banner_image_id?:0;
        $item->title 		= $request->title;
        $item->page          = $request->page;
        $item->short_description          = $request->short_description;
        $item->description          = $request->description;
        $item->blog_category_id          = $request->blog_category_id?:0;
        $item->slug          = $request->slug ? str_slug($request->slug) : str_slug($request->title);
        $item->meta_title          = $request->meta_title;
        $item->meta_description          = $request->meta_description;
        $item->meta_keywords     = $request->meta_keywords;
        $item->save();
        $redirectTo =route('admin.software.index');
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan('cms-two','delete')) return dd('No Permission');
        if($id = $request->id) {
            NgSoftware::find($id)->delete();
            ModulePackage::where('data_id', $id)->delete();
            ModuleService::where('data_id', $id)->delete();
            ModuleFeature::where('data_id', $id)->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
}
