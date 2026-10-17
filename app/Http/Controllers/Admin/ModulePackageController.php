<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ModulePackage;
use App\Model\ModuleFeature;
use App\Model\ModuleService;
use App\Model\Media;

class ModulePackageController extends Controller
{
    private $module = 'module_package';
    private $moduleName = 'Module Package';
    private $moduleNameSingle = 'module_package';

    public function index(Request $request)
     {
         $nav = $this->module;
         $breads = [
              ['name'=>'Cms2', 'url'=> route('admin.software.index')],
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         $data_id = $request->data_id;
         return view('admin.module_package.list', compact('nav','breads', 'moduleName', 'data_id'));
     }

     public function all(Request $request)
     {
        $data_id = $request->data_id;
          $results = ModulePackage::orderBy('id', 'desc')->where('data_id', $data_id);
          if($heading = $request->heading) {
              $results->where('heading', 'like', "%$heading%");
          }
          if($title = $request->title) {
              $results->where('title', 'like', "%$title%");
          }
           $results = $results->paginate(13);

           $data = [
               'rows' => view('admin.module_package.listrows',['results'=>$results, 'data_id'=>$data_id])->render(),
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
             ['name'=>$this->moduleName, 'url'=> route('admin.software.package', [$request->data_id])],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = ModulePackage::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();

         return view('admin.module_package.form', compact('nav', 'breads', 'item', 'medias', 'moduleName', 'data_id'));
     }

     public function submit(Request $request) {

       $id = $request->id;

         $this->validate($request, [
             'heading' => 'required|max:255|unique:module_packages,heading,'.$id.',id',
             'title' => 'required',
             'services' => 'required|array|min:2',
             'order' => 'required',
         ],[
             'heading.required' => 'Heading is Required',
             'title' => 'Title is Required',
             'services.required' => 'Services is Required',
         ]);

        $item = ModulePackage::findOrNew($id);
        $item->data_id 		= $request->data_id;
        $item->heading 		= $request->heading;
        $item->title 		= $request->title;
        $item->order 		= $request->order?:0;
        $item->save();
        if($request->services && count($request->services)){
          if(isset($request->services[0]) && $request->services[0]){
            ModuleService::where('package_id', $item->id)->delete();
            foreach ($request->services as $key => $serviceItem){
              $service = new ModuleService();
              $service->data_id = $request->data_id;
              $service->package_id = $item->id;
              $service->title = $serviceItem;
              $service->save();
            }
          }
        }
        $redirectTo =route('admin.software.package', [$request->data_id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if($id = $request->id) {
            ModulePackage::find($id)->delete();
            ModuleService::where('package_id', $id)->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request){
        if($id = $request->id){
            $item = ModulePackage::find($id);
            $vItem = $item->replicate();
            $vItem->heading = $item->heading .' Cloned';
            $vItem->save();
            $services = ModuleService::where('package_id', $id)->get();
            foreach ($services as $key => $value) {
                $sItem = ModuleService::find($value->id);
                $sItem = $sItem->replicate();
                $sItem->package_id = $vItem->id;
                $sItem->title = $sItem->title.' cloned';
                $sItem->save();
                $sItem->title = $sItem->title.' cloned-'.$sItem->id;
                $sItem->save();
            }

            return response()->json(['message'=>'Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in cloning data'], 401);
     }
}
