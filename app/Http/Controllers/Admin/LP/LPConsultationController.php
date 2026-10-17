<?php

namespace App\Http\Controllers\Admin\LP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\LP\LPConsulation;
use App\Model\Media;

class LPConsultationController extends Controller
{
  private $module = 'lpconsulation';
  private $moduleName = 'LPConsulation';
  private $moduleNameSingle = 'LPConsulation';

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

        $results = LPConsulation::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc')->where('type', 'sub-page');

        if($title = $request->title) {
            $results->where('title', 'like', "%$title%");
        }
        if(is_numeric($request->status)) {
          $status = $request->status;
          $results->where('status', "$status");
        }

        if($updated_at = $request->updated_at) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
         $results = $results->paginate(10);
         $nav = $this->module;
         $data = [
             'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'nav'=>$nav])->render(),
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
       $item = LPConsulation::findOrNew($id);

       return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item', 'moduleName'));
   }

   public function submit(Request $request) {

      $id = $request->id;

      if($id && !adminCan($this->module,'update')) return dd('No Permission');
      if(!$id && !adminCan($this->module,'add')) return dd('No Permission');


       $this->validate($request, [
           'name' => 'required|max:255|unique:l_p_consulations,title,'.$id.',id',

           'description' => 'required',
       ]);
      $id = $request->id;
      $item = LPConsulation::findOrNew($id);
      $item->image_id 		= $request->image_id?:0;
      $item->status 		= $request->status?:0;
      $item->display_social 		= $request->display_social?:0;
      $item->display_number 		= $request->display_number?:0;
      $item->display_email 		= $request->display_email?:0;
      $item->display_social 		= $request->display_social?:0;
      $item->title             = $request->title;
      $item->slug          = str_slug($request->slug?:$request->title);
      $item->meta_keywords          = $request->meta_keywords;
      $item->meta_title          = $request->meta_title;
      $item->meta_description          = $request->meta_description;
      $item->header_script          = $request->header_script;
      $item->footer_script          = $request->footer_script;
      $item->faqs          = $request->faqs;
      $item->testimonials          = $request->testimonials;
      $item->video_testimonials          = $request->video_testimonials;
      $item->section_one          = $request->section_one;
      $item->section_two          = $request->section_two;
      $item->section_three          = $request->section_three;
      $item->section_four          = $request->section_four;
      $item->section_five          = $request->section_five;
      $item->items          = $request->items;
      $item->save();
      $redirectTo =  route('admin.'.$this->module.'.edit', $item->id);
      return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
   }
   public function delete(Request $request)
   {
      if(!adminCan($this->module,'delete')) return dd('No Permission');
      if($id = $request->id) {
          $item = LPConsulation::find($id);
          $item->delete();
          return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
   }
}
