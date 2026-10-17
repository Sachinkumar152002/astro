<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Module;
use App\Model\Permission;

class ModuleController extends Controller
{

    private $module = 'module';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!adminCan($this->module,'view')) return dd('No Permission');
      $nav = 'module';
       $breads = [
           ['name'=>'Module']
       ];
        return view('admin.module.list', compact('nav','breads'));
    }

    /**/
    public function submit(Request $request)
    {
        if(!adminCan($this->module,'view')) return dd('No Permission');
        $id = $request->module_id;
        $this->validate($request, [
            'key' => 'required|max:150|unique:modules,key'.($id?','.$id.',id':''),
            'name' => 'required|max:100'
        ]);

        if($id) {
            $module = Module::find($id);
        } else {
            $module = new Module();
        }
        $module->name = $request->name;
        $module->key = str_replace(' ', '-', $request->key);
        $module->save();
        return response()->json(['message'=>'Succcessfully saved!'], 200);
        return response()->json(['message'=>'Error in saving'], 401);

    }

    /**/
    public function all(Request $request)
    {

       if(!adminCan($this->module,'view')) return dd('No Permission');

       $module = Module::orderBy($request->sort_by,$request->sort_dir);

          if($name = $request->name){

            $module->where('name','like', "%$name%");
          }

           if($key = $request->key){

            $module->where('key','like', "%$key%");
          }

          $results = $module->paginate(10);
          $data = [
              'rows' => view('admin.module.listrows',['results'=>$results])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),

          ];
          return response()->json($data, 200);
    }

    /**/
    public function delete(Request $request)
    {
      if($module_id = $request->id) {
        $module = Module::find($module_id);
        if(Permission::where('module_id',$module_id)->first()){
          return response()->json(['message'=>"Can't delete. Permission exist of this module."], 401);
        }
        $module->delete();
        return response()->json(['message'=>'Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in delete'], 401);
    }


}
