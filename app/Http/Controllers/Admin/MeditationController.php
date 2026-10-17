<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Meditation;
use App\Model\Media;

class MeditationController extends Controller
{
    private $module = 'meditation';
    private $moduleName = 'Meditation';
    private $moduleNameSingle = 'Meditation';

    public function index(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');

          $results = Meditation::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc')->where('type', 'sub-page');

          if($name = $request->name) {
              $results->where('name', 'like', "%$name%");
          }
          if(is_numeric($request->status)) {
            $status = $request->status;
            $results->where('status', "$status");
    }
          if($updated_at = $request->updated_at) {
              $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }
           $results = $results->paginate(23);

           $data = [
               'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {
        $id = $request->id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');

        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = Meditation::findOrNew($id);
         $medias  = Media::where('type',$nav)->get();
         if(in_array($item->type, ['page'])) {
            $nav .= '_page';
         }

         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item', 'medias', 'moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');

         $this->validate($request, [
             'name' => 'required|max:255|unique:meditations,name,'.$id.',id',

             'description' => 'required',
         ]);
        $id = $request->id;
        $item = Meditation::findOrNew($id);
        $item->image_id 		= $request->image_id?:0;
        $item->banner_image_id 		= $request->banner_image_id?:0;
        $item->status 		= $request->status?:0;
        $item->name             = $request->name;
        $item->description          = $request->description;
        $item->meta_keywords          = $request->meta_keywords;
        $item->meta_title          = $request->meta_title;
        $item->meta_description          = $request->meta_description;
        if(!$id){
            $item->type = 'sub-page';
        }
        if($item->type == 'sub-page'){
            $item->slug             = str_slug($request->name);
        }
        $item->save();
        $redirectTo = $item->type=='page' ? route('admin.'.$this->module.'.edit', $item->id) : route('admin.'.$this->module.'.index')  ;
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }
     public function delete(Request $request)
     {
        if(!adminCan($this->module,'delete')) return dd('No Permission');
        if($id = $request->id) {
            $item = Meditation::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
 }
