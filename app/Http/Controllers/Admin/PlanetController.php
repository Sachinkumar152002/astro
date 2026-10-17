<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Planet;
use App\Model\Media;

class PlanetController extends Controller
{

    private $module = 'planets';
    private $moduleName = 'Planets';
    private $moduleNameSingle = 'Planet';

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

          $results = Planet::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc')->where('page_type', 'planet');

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
           $results = $results->paginate(9);

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
         $item = Planet::findOrNew($id);
         $medias  = Media::where('type','planets')->get();
         if(in_array($item->slug, ['retrograde', 'transit', 'planets'])) {
            $nav .= '_'.$item->slug;
         }
        //  dd($medias);
         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item', 'medias', 'moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;

        if($id && !adminCan($this->module,'update')) return dd('No Permission');
        if(!$id && !adminCan($this->module,'add')) return dd('No Permission');


         $this->validate($request, [
             'name' => 'required|max:255',

             'description' => 'required',
         ]);

         $id = $request->id;

        $item = Planet::findOrNew($id);
        $item->image_id 		= $request->image_id?:0;
        $item->banner_image_id 		= $request->banner_image_id?:0;
        $item->status 		= $request->status?:0;
        $item->name             = $request->name;
        $item->description          = $request->description;
        $item->meta_keywords          = $request->meta_keywords;
        $item->meta_title          = $request->meta_title;
        $item->meta_description          = $request->meta_description;
        $item->save();
        $redirectTo = $item->page_type=='planet' ? route('admin.'.$this->module.'.index') : route('admin.'.$this->module.'.edit', $item->id);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan($this->module,'delete')) return dd('No Permission');
        if($id = $request->id) {
            $item = Planet::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
 }
