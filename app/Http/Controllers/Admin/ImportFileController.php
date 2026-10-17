<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Artisan;
use File;
use App\Model\File as FileModel;

class ImportFileController extends Controller
{

    private $module = 'importfile';
    private $moduleName = 'Import File';
    private $moduleNameSingle = 'importFile';

    public function index(Request $request)
     {
       $nav = $this->module;
       if(!adminCan($nav, 'view')) return dd('No Permission');
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request){
       $results = FileModel::orderBy('updated_at', 'desc');

       if($type = $request->type){
         $results->where('type', 'like', "%$type%");
       }
       if(isset($request->is_processed) && is_numeric($request->is_processed)) {
         $results->where('is_processed', $request->is_processed);
       }
       if($updated_at_from = $request->updated_at_from){
         $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to){
         $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
       $results = $results->paginate(16);

       $data = [
         'rows' => view('admin.'.$this->module.'.listrows', ['results' => $results, 'module' => $this->module])->render(),
         'pagination' => view('admin._partials.pagination', ['results' => $results])->render(),
       ];
       return response()->json($data, 200);
     }

     public function submit(Request $request){

       $this->validate($request, [
         // 'file' => 'required|file|mimes:csv,text',
         'module' => 'required',
       ]);
       $nav = $request->module;

       if(!adminCan($nav, 'add')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }

       $filePath = public_path('import');
       if(!File::exists($filePath)) {
           File::makeDirectory($filePath, 0777, true);
       }
       $filePath = public_path('import/csv');
       if(!File::exists($filePath)) {
           File::makeDirectory($filePath, 0777, true);
       }
       $time = time();
       $fileName = $time.'.'.$request->file->getClientOriginalExtension();
       $request->file->move($filePath, $fileName);

       $fileModel = new FileModel();
       $fileModel->url = 'import/csv/'.$fileName;
       $fileModel->type = $request->type;
       $fileModel->save();
       Artisan::call("import:$fileModel->type");
       $redirectTo = '';
       return response()->json(['message'=> 'Saved Successfully. Wait file processing.', 'redirectto'=>$redirectTo], 200);
     }
}
