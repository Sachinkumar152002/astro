<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Cms;
use App\Model\Media;
use App\Model\QuestionAnswer;

class CmsController extends Controller
{
    private $module = 'cms';
    private $moduleName = 'Pages';
    private $moduleNameSingle = 'Page';

    public function index(Request $request)
     {
         if(!adminCan('cms','view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.cms.list', compact('nav','breads', 'moduleName'));
     }
     public function about() {
       $nav = 'about';
         $breads = [
             ['name'=>'About']
         ];
       return view('admin.aboutUs.about', compact('nav','breads'));
     }

     public function all(Request $request)
     {
        $cms = Cms::orderBy($request->sort_by,$request->sort_dir);
        $cms->where('hide_from_list', 0);

        if($name = $request->name) {
            $cms->where('name', 'like', "%$name%");
        }
        if($template = $request->template) {
            $cms->where('template',$template);
        }
        if( $slug = $request->slug) {
            $cms->where('slug',  'like', "%$slug%");
        }
        if(isset($request->status) && is_numeric($request->status)) {$status = $request->status;
            $cms->where('status', $status);
        }

        if($created_at = $request->created_at) {
            $cms->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ? " , $created_at);
        }
        if($updated_at = $request->updated_at) {
            $cms->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
         $cms = $cms->paginate(10);
           $data = [
               'rows' => view('admin.cms.listrows',['results'=>$cms])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$cms])->render(),
           ];
           return response()->json($data, 200);
     }

     public function form(Request $request)
     {
         $id = $request->id;
        $moduleName = $this->moduleNameSingle;
        $cms = Cms::findOrNew($id);
        $medias  = Media::get();
        if($cms->hide_from_list) {
            $nav = $cms->slug;
            $breads = [
                ['name'=>$cms->name]
            ];
        }else {
            $nav = 'cms';
            $breads = [
                ['name'=>$this->moduleName, 'url'=> route('admin.cms.index')],
                ['name'=>($id ? 'Edit': 'Add')]
            ];
        }
        return view('admin.cms.form', compact('nav', 'breads', 'cms', 'medias', 'moduleName'));
     }

     public function submit(Request $request) {
         $id = $request->id;
         if(!$id && !adminCan('cms', 'add')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
         if($id && !adminCan('cms', 'update')){
           return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
         }
         $this->validate($request, [
           'name' => 'required|max:255',
           'slug' => 'required|max:255|unique:cms,slug,'.$id.',id',
           'status' => 'required',
           'content' => 'required',
           'template' => 'required'
         ]);
         $cms = Cms::findOrNew($id);
         $cms->name 			= $request->name;
         $cms->slug 			= str_slug($request->slug);
         $cms->content 			= $request->content;
         $cms->short_description 			= $request->short_description;
         $cms->parent_page_id 			= $request->parent_page_id?:0;
         $cms->image_id 			= $request->image_id?:0;
         $cms->banner_image_id 			= $request->banner_image_id?:0;
         $cms->meta_title        = $request->meta_title;
         $cms->meta_keywords        = $request->meta_keywords;
         $cms->meta_description        = $request->meta_description;


        if(!$cms->hide_from_list){
            $cms->template 		    = $request->template;
            $cms->status 		    = $request->status;
            $cms->show_in_navigation 		    = $request->show_in_navigation;
            $cms->display_in_user_app 	    = $request->has('display_in_user_app') ? 1 : 0;
            $cms->display_in_customer_app 	= $request->has('display_in_customer_app') ? 1 : 0;
        }

         $cms->save();

         $page_id = $cms->id;

        if($cms->template=='default') {
            QuestionAnswer::where('page_id', $page_id)->delete();
        }

        if( in_array($cms->template, ['content']) ) {
            $questions = json_decode($request->questions);
            $oldIds = collect($questions)->pluck('id');
            QuestionAnswer::where('page_id', $page_id)->whereNotIn('id', $oldIds)->delete();
            if($questions) {
            foreach($questions as $order=>$row) {
                $question  = $row->id ? QuestionAnswer::find($row->id) : new QuestionAnswer();
                $question->question = $row->question;
                $question->answer = $row->answer;
                $question->page_id = $page_id;
                $question->order = $order;
                $question->save();
            }
          }
         }
         $redirectUrl = route('admin.cms.index');
         if($cms->hide_from_list) {
            $redirectUrl = route('admin.cms.edit', $cms->id);
         }
         return response()->json(['message'=>sprintf('%s Saved Successfully', $this->moduleNameSingle), 'redirectto'=>$redirectUrl], 200);

         return $request;
     }

    public function delete(Request $request)
     {
     if(!adminCan('cms','delete')) return dd('No Permission');
       if($id = $request->id) {
            QuestionAnswer::where('page_id', $id)->delete();
            $cms = Cms::find($id);
            $cms->delete();
          return response()->json(['message'=>sprintf('%s Deleted successfully!', $this->moduleNameSingle)], 200);
       }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request)
      {
      if(!adminCan('cms','add')) return dd('No Permission');
    if($id = $request->id) {
    $item = Cms::find($id);
    $nitem = $item->replicate();
    $nitem->name = $item->name.' Cloned';
          $nitem->slug = $item->slug.'-'.$item->id;
          $nitem->save();
          $QuestionAnswer = QuestionAnswer::where('page_id', $id)->get();
          foreach($QuestionAnswer as $s){
            $ns = $s->replicate();
            $ns->page_id = $nitem->id;
            $ns->save();
          }
    return response()->json(['message'=>'Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
      }
 }
