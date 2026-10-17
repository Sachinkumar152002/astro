<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ModuleProcedure;
use App\Model\Media;

class ModuleProcedureController extends Controller
{
    private $module = 'module_procedure';
    private $moduleName = 'Module Procedures';
    private $moduleNameSingle = 'module_procedure';

    public function index(Request $request)
     {
         $nav = $this->module;
         $breads = [
              ['name'=>'Cms2', 'url'=> route('admin.software.index')],
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         $data_id = $request->data_id;
         return view('admin.module_procedure.list', compact('nav','breads', 'moduleName', 'data_id'));
     }

     public function all(Request $request)
     {
        $data_id = $request->data_id;
          $results = ModuleProcedure::orderBy('id', 'desc')->where('data_id', $data_id);
          if($title = $request->title) {
              $results->where('title', 'like', "%$title%");
          }
           $results = $results->paginate(13);

           $data = [
               'rows' => view('admin.module_procedure.listrows',['results'=>$results, 'data_id'=>$data_id])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {

       $id = $request->id;
        $data_id = $request->data_id;
        $nav = $this->module;
        $moduleName = $this->moduleName;
        $breads = [
             ['name'=>'Cms2', 'url'=> route('admin.software.index')],
             ['name'=>$this->moduleName, 'url'=> route('admin.software.procedure', [$request->data_id])],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = ModuleProcedure::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();

         return view('admin.module_procedure.form', compact('nav', 'breads', 'item', 'medias', 'moduleName', 'data_id'));
     }

     public function submit(Request $request) {

       $id = $request->id;
        $data_id = $request->data_id;
        $this->validate($request, [
            'title' => 'required|max:255|unique:module_procedures,title,'.$id.',id',
            'title' => 'required|max:255|unique:module_procedures,title,'.$data_id.',data_id',
            'image_id'=>'required|numeric',
            'description' => 'required',
            'order' => 'required',
        ]);

        $item = ModuleProcedure::findOrNew($id);
        $item->data_id 		= $data_id?:0;
        $item->image_id 		= $request->image_id?:0;
        $item->title             = $request->title;
        $item->description          = $request->description;
        $item->order 		= $request->order?:0;
        $item->save();
        $redirectTo =route('admin.software.procedure', [$data_id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if($id = $request->id) {
            ModuleProcedure::find($id)->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request){
        if($id = $request->id){
            $item = ModuleProcedure::find($id);
            $vItem = $item->replicate();
            $vItem->title = $item->title .' Cloned';
            $vItem->save();
            return response()->json(['message'=>'Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in cloning data'], 401);
     }
}
