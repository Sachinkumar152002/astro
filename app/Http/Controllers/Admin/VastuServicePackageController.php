<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Media;
use App\Model\VastuServicePackage;
use App\Model\VastuServicePackageName;

class VastuServicePackageController extends Controller
{
    private $module = 'package';
    private $moduleName = 'Package';
    private $moduleNameSingle = 'Package';

    public function index(Request $request)
     {
         $nav = $this->module;
         $breads = [
              ['name'=>'Vastu', 'url'=> route('admin.vastu.index')],
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         $service_id = $request->service_id;
         return view('admin.vastu.packages.list', compact('nav','breads', 'moduleName', 'service_id'));
     }

     public function all(Request $request)
     {
       $service_id = $request->service_id;
          $results = VastuServicePackage::orderBy('id', 'desc')->where('service_id', $service_id)->where('status', 1);

          if($heading = $request->heading) {
              $results->where('heading', 'like', "%$heading%");
          }
          if($title = $request->title) {
              $results->where('title', 'like', "%$title%");
          }
          if(is_numeric($request->status)) {
            $status = $request->status;
            $results->where('status', "$status");
    }
           $results = $results->paginate(13);

           $data = [
               'rows' => view('admin.vastu.packages.listrows',['results'=>$results, 'service_id'=>$service_id])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {
        $id = $request->id;
        $service_id = $request->service_id;
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>'Vastu', 'url'=> route('admin.vastu.index')],
             ['name'=>$this->moduleName, 'url'=> route('admin.vastu.package', [$request->service_id])],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = VastuServicePackage::find($id);
         if(!$item){
           $item = false;
         }
         $medias  = Media::where('type',$nav)->get();

         return view('admin.vastu.packages.form', compact('nav', 'breads', 'item', 'medias', 'moduleName', 'service_id'));
     }

     public function submit(Request $request) {
       // dd($request->all());

        $id = $request->id;
         $this->validate($request, [
           'heading' => 'required|max:255|unique:vastu_service_packages,heading,'.$id.',id',
             'title' => 'required|not_in:0',
             'services' => 'required|array|min:1',
         ],[
           'heading' => 'Heading is Required',
           'title.required' => 'Price is Required',
           'title.not_in' => 'Price is greater than 0',
           'services.array' => 'Services is Required',
         ]);

        $item = VastuServicePackage::findOrNew($id);
        $item->service_id 		= $request->service_id;
        $item->heading 		= $request->heading?:'';
        $item->title 		= $request->title;
        $item->status 		= $request->status?:1;
        $item->save();
        if($request->services && count($request->services)){
          if(isset($request->services[0]) && $request->services[0]){
            VastuServicePackageName::where('package_id', $item->id)->delete();
            foreach ($request->services as $key => $serviceItem){
              $service = new VastuServicePackageName();
              $service->service_id = $request->service_id;
              $service->package_id = $item->id;
              $service->name = $serviceItem;
              $service->save();
            }
          }
        }
        $redirectTo =route('admin.vastu.package', [$request->service_id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if($id = $request->id) {
            $item = VastuServicePackage::find($id);
            $item->delete();
            VastuServicePackageName::where('package_id', $id)->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request)
      {
    if($id = $request->id) {
    $item = VastuServicePackage::find($id);
    $nitem = $item->replicate();
          $nitem->save();
    $nitem->heading = $item->heading.' Cloned-'.$nitem->id;
          $nitem->save();
          $VastuServicePackageName = VastuServicePackageName::where('package_id', $id)->get();
           foreach($VastuServicePackageName as $s){
             $ns = $s->replicate();
             $ns->package_id = $nitem->id;
             $ns->save();
           }
    return response()->json(['message'=>' Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
      }
}
