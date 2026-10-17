<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Language;

class LanugageController extends Controller
{

    private $module = 'language';
    private $moduleName = 'Languages';
    private $moduleNameSingle = 'Language';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(!adminCan('language','view')) return dd('No Permission');
      $nav = $this->module;
      $modulename = $this->moduleName;
      $modulenamesingle = $this->moduleName;
      $breads = [
          ['name'=>$this->moduleName]
      ];
      return view('admin.'.$this->module.'.list', compact('nav','breads', 'modulename', 'modulenamesingle'));
    }

    /**/
    public function submit(Request $request)
    {
      $id = $request->item_id;
      if(!$id && !adminCan('language', 'add')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      if($id && !adminCan('language', 'update')){
        return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
      }
      $this->validate($request, [
        'name' => 'required|max:150|unique:languages,name,'.$id.',id',
        // 'slug' => 'required|max:150|unique:languages,slug'.($id?','.$id.',id':''),
      ]);

      $item = Language::findOrNew($id);
      $item->name = $request->name;
      $item->position = $request->position?:0;
      $item->slug = str_slug($request->name);
      $item->save();
      if($item->save()){
          return response()->json(['message'=>'Succcessfully saved!'], 200);
      }
      return response()->json(['message'=>'Error in saving'], 401);
    }

    /**/
    public function all(Request $request)
    {
       $items = Language::orderBy($request->sort_by,$request->sort_dir);
       if($name = $request->name){
         $items->where('name','like', "%$name%");
       }
       if(is_numeric($request->position)){
         $items->where('position', $request->position);
       }
      $results = $items->paginate(10);
      $data = [
          'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),

      ];
      return response()->json($data, 200);
    }

    /**/
    public function delete(Request $request)
    {
      if(!adminCan('language','delete')) return dd('No Permission');
      if($item_id = $request->item_id) {
        $item = Language::find($item_id);
        $item->delete();
          return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in delete'], 401);
    }

}
