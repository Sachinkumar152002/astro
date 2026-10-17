<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ModulePackage;
use App\Model\ModuleFeature;
use App\Model\ModuleService;
use App\Model\Media;

class ModuleFeatureController extends Controller
{
    private $module = 'module_feature';
    private $moduleName = 'Module Features';
    private $moduleNameSingle = 'module_feature';

    public function index(Request $request)
     {
         $nav = $this->module;
         $breads = [
              ['name'=>'Cms2', 'url'=> route('admin.software.index')],
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         $data_id = $request->data_id;
         return view('admin.module_feature.list', compact('nav','breads', 'moduleName', 'data_id'));
     }

     public function all(Request $request)
     {
        $data_id = $request->data_id;
          $results = ModuleFeature::orderBy('id', 'desc')->where('data_id', $data_id);
          if($title = $request->title) {
              $results->where('title', 'like', "%$title%");
          }
           $results = $results->paginate(13);

           $data = [
               'rows' => view('admin.module_feature.listrows',['results'=>$results, 'data_id'=>$data_id])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {

       $id = $request->id;
        $data_id = $request->data_id;
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>'Cms2', 'url'=> route('admin.software.index')],
             ['name'=>$this->moduleName, 'url'=> route('admin.software.feature', [$request->data_id])],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = ModuleFeature::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();

         return view('admin.module_feature.form', compact('nav', 'breads', 'item', 'medias', 'moduleName', 'data_id'));
     }

     public function submit(Request $request) {

       $id = $request->id;

         $this->validate($request, [
             'title' => 'required|max:255|unique:module_features,title,'.$id.',id',
             'image_id' => 'required|numeric|not_in:0',
             'order' => 'required|numeric|not_in:0',
         ],[
             'title' => 'Title is Required',
             'image_id' => 'Image is Required',
             'order.required' => 'Order is Required',
         ]);

        $item = ModuleFeature::findOrNew($id);
        $item->data_id 		= $request->data_id?:0;
        $item->image_id 		= $request->image_id?:0;
        $item->title 		= $request->title;
        $item->order 		= $request->order?:0;
        $item->save();
        $redirectTo =route('admin.software.feature', [$request->data_id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if($id = $request->id) {
            ModuleFeature::find($id)->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request){
        if($id = $request->id){
            $item = ModuleFeature::find($id);
            $vItem = $item->replicate();
            $vItem->title = $item->title .' Cloned';
            $vItem->save();
            return response()->json(['message'=>'Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in cloning data'], 401);
     }
}
