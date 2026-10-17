<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Landing;

class LandingController extends Controller
{

    private $module = 'landingpage';
    private $moduleName = 'Landing';
    private $moduleNameSingle = 'Landing';

    public function index(Request $request)
     {
       if(!adminCan('landing','view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
          $results = Landing::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

          if($title = $request->title) {
              $results->where('title', 'like', "%$title%");
          }
          if($code = $request->code) {
            $results->where('code', 'like', "%$code%");
          }
          if(isset($request->status) && is_numeric($request->status)) {
            $results->where('status', $request->status);
          }
          if(isset($request->show_in_navigation) && is_numeric($request->show_in_navigation)) {
            $results->where('show_in_navigation', $request->show_in_navigation);
          }
          if($updated_at = $request->updated_at) {
              $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
          }
           $results = $results->paginate(16);

           $data = [
               'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'module' => $this->module])->render(),
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
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = Landing::find($id);
         if(!$item){
           $item = null;
         }
         return view('admin.'.$this->module.'.form', compact('nav', 'breads', 'item','moduleName'));
     }

     public function submit(Request $request) {

        $id = $request->id;
        if(!$id && !adminCan('landing', 'add')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
        if($id && !adminCan('landing', 'update')){
          return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
        }
         $this->validate($request, [
             'title' => 'required|max:255',
             'slug' => 'required|unique:landings,slug,'.$id.',id',
         ]);

        $page = Landing::findOrNew($id);
        $page->icon_id = $request->icon_id?:0;
        $page->title = $request->title;
        $page->slug = str_slug($request->slug);
        $page->style = $request->style;
        $page->header_script = $request->header_script;
        $page->content = Null;
        $page->footer_script = $request->footer_script;
        $page->meta_title = $request->meta_title;
        $page->meta_keywords = $request->meta_keywords;
        $page->meta_description = $request->meta_description;
        $page->status = $request->status?:0;
        $page->show_in_navigation = $request->show_in_navigation?:0;
        $page->save();
        $redirectTo = route('admin.'.$this->module.'.edit', [$page->id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request){
        if(!adminCan('landing','delete')) return dd('No Permission');
        if($id = $request->id) {
            $page = Landing::find($id);
            $page->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

    public function duplicate(Request $request){
      if(!adminCan('landing','add')) return dd('No Permission');
      if($id = $request->id){
        $page = Landing::find($id);
        $newPage = $page->replicate();
        $newPage->title = $newPage->title.' Cloned';
        $newPage->save();
        $newPage->slug = $newPage->title.'-cloned-'.$newPage->id;
        $newPage->save();
       // return redirect->back()->response()->json(['message'=>'Cloned successfully!'], 200);
        return redirect()->back()->with('success', 'Page cloned successfully!');
      }
     // return redirect->back()response()->json(['message'=>'Error in Cloning data'], 401);
      return redirect()->back()->with('error', 'Error in cloning the page.');
    }


      public function editor(Request $request, $id)
      {
          $page = Landing::findOrFail($id);

          return view('admin.' . $this->module . '.editor', compact('page'));
      }

      public function pagesave(Request $request, $id)
      {
          $page = Landing::findOrFail($id);
          //$page->html = $request->html;
          $page->html = $request->input('html');
          $page->css = $request->input('css');
          $page->components = $request->input('components');
          $page->styles = $request->input('styles');
          $page->save();

          return response()->json(['status' => 'Saved']);
      }


}
