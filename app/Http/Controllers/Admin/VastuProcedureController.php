<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\{VastuProcedure, Media};

class VastuProcedureController extends Controller
{
    private $module = 'vastu_procedure';
    private $moduleName = 'Procedure';
    private $moduleNameSingle = 'Procedure';

    public function index(Request $request)
     {
      if(!adminCan('vastu-procedure','view')) return dd('No Permission');
       $nav = $this->module;
       $breads = [
           ['name'=>$this->moduleName]
       ];
       $moduleName = $this->moduleName;
       return view('admin.vastu_procedure.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
        $results = VastuProcedure::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

        if($title = $request->name) {
            $results->where('title', 'like', "%$title%");
        }
        if($type = $request->type) {
            $results->where('type', $type);
        }
        if(is_numeric($request->status)) {
          $status = $request->status;
          $results->where('status', $status);
        }
        if(isset($request->show_on_services) && is_numeric($request->show_on_services)) {
          $show_on_services = $request->show_on_services;
          $results->where('show_on_services', $show_on_services);
        }
        if($updated_at = $request->updated_at) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
         $results = $results->paginate(13);

         $data = [
             'rows' => view('admin.vastu_procedure.listrows',['results'=>$results])->render(),
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
             ['name'=>$this->moduleName, 'url'=> route('admin.vastu.procedure.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = VastuProcedure::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();

         return view('admin.vastu_procedure.form', compact('nav', 'breads', 'item', 'medias', 'moduleName'));
     }

     public function submit(Request $request) {
        $id = $request->id;
        if(!$id && !adminCan('vastu-procedure', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('vastu-procedure', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }

         $this->validate($request, [
           'type' => 'required',
             'title' => 'required|max:255|unique:vastu_procedures,title,'.$id.',id',
             'image_id'=>'required|numeric',
             'description' => 'required',
         ]);

        $item = VastuProcedure::findOrNew($id);
        $item->image_id = $request->image_id?:0;
        $item->status = $request->status?:0;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->type = $request->type;
        $item->show_on_services = $request->show_on_services?:0;
        $item->save();
        $redirectTo = route('admin.vastu.procedure.index');
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
       if(!adminCan('vastu-procedure','delete')) return dd('No Permission');
        if($id = $request->id) {
            $item = VastuProcedure::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

      public function duplicate(Request $request)
      {
        if(!adminCan('vastu-procedure','add')) return dd('No Permission');
        if($id = $request->id) {
          $item = VastuProcedure::find($id);
          $nitem = $item->replicate();
          $nitem->title = $item->title.' Cloned';
          $nitem->save();
          return response()->json(['message'=>'Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in Cloning data'], 401);
      }
}
