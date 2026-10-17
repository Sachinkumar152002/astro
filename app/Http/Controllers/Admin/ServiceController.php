<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Service;

class ServiceController extends Controller
{

    private $module = 'service';
    private $moduleName = 'Astrologer Categories';
    private $moduleNameSingle = 'Service';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!adminCan($this->module, 'view')) return dd('No Permission');
        $nav = $this->module;
        $modulename = $this->moduleName;
        $modulenamesingle = $this->moduleName;
        $breads = [
            ['name'=>$this->moduleName]
        ];
        return view('admin.'.$this->module.'.list', compact('nav','breads', 'modulename', 'modulenamesingle'));
    }

    public function all(Request $request)
    {
       $items = Service::orderBy($request->sort_by,$request->sort_dir);

          if($name = $request->name){
            $items->where('name','like', "%$name%");
          }
           if($slug = $request->slug){
            $items->where('slug','like', "%$slug%");
          }
        //   dd($items->toSql());
          $results = $items->paginate(10);
          $data = [
              'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),

          ];
          return response()->json($data, 200);
    }

    public function submit(Request $request)
    {
        $id = $request->item_id;
        if(!$id && !adminCan($this->module, 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan($this->module, 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        $this->validate($request, [
            'name' => 'required|max:100|unique:services,name,'.$id.',id',
            'icon_id'=>'required',
            // 'slug' => 'required|max:150|unique:services,slug'.($id?','.$id.',id':''),
        ]);
        $item = Service::findOrNew($id);
        $item->icon_id = $request->icon_id;
        $item->name = $request->name;
        $item->short_name =$request->short_name;
        $item->slug = str_slug($request->name);
        $item->save();
        if($item->save()){
            return response()->json(['message'=>'Succcessfully saved!'], 200);
        }
        return response()->json(['message'=>'Error in saving'], 401);
    }

    /**/
    public function delete(Request $request)
    {
      if(!adminCan($this->module, 'delete')){
        return response()->json(['message'=>'No Permission'], 402);
      }
      if($item_id = $request->item_id) {
        $item = Service::find($item_id);
        $item->delete();
          return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in delete'], 401);
    }

}
