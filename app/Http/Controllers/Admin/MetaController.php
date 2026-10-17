<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TemplateMeta;

class MetaController extends Controller
{
    private $module = 'meta';
    public function index(Request $request)
     {
         if(!adminCan($this->module,'view')) return dd('No Permission');

         $nav = $this->module;
         $breads = [
             ['name'=>'Meta Template']
         ];

         return view('admin.metas.list', compact('nav','breads'));
     }

     public function all(Request $request)
     {
        if(!adminCan($this->module,'view')) return dd('No Permission');

        $templateMeta = TemplateMeta::orderBy($request->sort_by,$request->sort_dir);

        if($meta_title = $request->meta_title) {
            $templateMeta->where('meta_title', 'like', "%$meta_title%");
        }
        if($meta_keywords = $request->meta_keywords) {
            $templateMeta->where('meta_keywords', 'like', "%$meta_keywords%");
        }
        if($meta_description = $request->meta_description) {
            $templateMeta->where('meta_description', 'like', "%$meta_description%");
        }
        if(isset($request->status) && is_numeric($request->status)) {
            $status = $request->status;
            $templateMeta->where('status', $status);
        }

        if($updated_at = $request->updated_at) {
            $templateMeta->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }

        $templateMetas = $templateMeta->paginate(10);

        $data = [
            'rows' => view('admin.metas.listrows',['results'=>$templateMetas])->render(),
            'pagination' => view('admin._partials.pagination',['results'=>$templateMetas])->render(),
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
             ['name'=>'Meta Template', 'url'=> route('admin.meta.index')],
             ['name'=>($id ? 'Edit': 'Add')]
         ];
         $templateMeta = false;
         if($id) { $templateMeta = TemplateMeta::find($id); }

         return view('admin.metas.form', compact('nav', 'breads', 'templateMeta' ));
     }

     public function submit(Request $request) {
         $id = $request->id;

         if($id && !adminCan($this->module,'update')) return dd('No Permission');
         if(!$id && !adminCan($this->module,'add')) return dd('No Permission');

         $this->validate($request, [
             'meta_title' => 'required|max:255',
             'meta_keywords' => 'required|max:255',
             'meta_description' => 'required|max:255',
             'overwrite' => 'required',
             'status' => 'required',
         ]);

        $id = $request->id;
        if($id){
            $templateMeta = TemplateMeta::find($id);
        }else {
            $templateMeta = new TemplateMeta();
        }

         $templateMeta->meta_title 			= $request->meta_title;
         $templateMeta->meta_keywords 			    = $request->meta_keywords;
         $templateMeta->meta_description 			= $request->meta_description;
         $templateMeta->status 			= $request->status;
         $templateMeta->overwrite 			= json_encode($request->overwrite ?  $request->overwrite : []);

         $templateMeta->save();
         return response()->json(['message'=>'Meta template Saved Successfully', 'redirectto'=>route('admin.meta.index')], 200);
     }

     public function delete(Request $request)
     {
        if(!adminCan($this->module,'delete')) return dd('No Permission');
        if($id = $request->id) {
            $templateMeta = TemplateMeta::find($id);
            $templateMeta->delete();
            return response()->json(['message'=>'Meta Temaplate Deleted successfully!'], 200);
        }
       return response()->json(['message'=>'Error in deleting data'], 401);
     }

 }
