<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TemplateContent;
use App\Model\Media;

class ContentController extends Controller
{
    private $module = 'content';
    public function index(Request $request)
     {
         if(!adminCan($this->module,'view')) return dd('No Permission');

         $nav = $this->module;
         $breads = [
             ['name'=>'Content Template']
         ];

         return view('admin.contents.list', compact('nav','breads'));
     }

     public function all(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');

        $templateContent = TemplateContent::orderBy($request->sort_by,$request->sort_dir);

        if($content = $request->content) {
            $templateContent->where('content', 'like', "%$content%");
        }

        if($updated_at = $request->updated_at) {
            $templateContent->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }
        if(isset($request->status) && is_numeric($request->status)) {
            $status = $request->status;
            $templateContent->where('status', $status);
        }

        $templateContents = $templateContent->paginate(10);

        $data = [
            'rows' => view('admin.contents.listrows',['results'=>$templateContents])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$templateContents])->render(),
        ];
        return response()->json($data, 200);
     }

     public function form(Request $request)
     {
         $id = $request->id;

         if($id && !adminCan($this->module,'update')) return dd('No Permission');
         if(!$id && !adminCan($this->module,'add')) return dd('No Permission');
             $nav = $this->module;

         $breads = [
             ['name'=>'Content Template', 'url'=> route('admin.content.index')],
             ['name'=>($id ? 'Edit': 'Add')]
         ];
         $templateContent = false;
         if($id) { $templateContent = TemplateContent::find($id); }
         $medias  = Media::get();
         return view('admin.contents.form', compact('nav', 'breads', 'templateContent' , 'medias'));
     }

     public function submit(Request $request) {
         $id = $request->id;

         if($id && !adminCan($this->module,'update')) return dd('No Permission');
         if(!$id && !adminCan($this->module,'add')) return dd('No Permission');

         $this->validate($request, [
             'content' => 'required',
             'status' => 'required',
             'overwrite' => 'required',
         ]);

        $id = $request->id;
        if($id){
            $templateContent = TemplateContent::find($id);
        }else {
            $templateContent = new TemplateContent();
        }

         $templateContent->content 			= $request->content;
         $templateContent->status 			= $request->status;
         $templateContent->overwrite 		= json_encode($request->overwrite ?  $request->overwrite : []);

         $templateContent->save();
         return response()->json(['message'=>'Content template Saved Successfully', 'redirectto'=>route('admin.content.index')], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan($this->module,'delete')) return dd('No Permission');
        if($id = $request->id) {
            $templateContent = TemplateContent::find($id);
            $templateContent->delete();
            return response()->json(['message'=>'Content template Deleted successfully!'], 200);
        }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

 }
