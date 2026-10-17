<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CallHistory;

class CallHistoryController extends Controller
{
    private $module = 'callhistory';
    private $moduleName = 'Call History';
    private $moduleNameSingle = 'Call History';

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

          $results = CallHistory::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

          if($from_number = $request->from_number) {
              $results->where('from_number', 'like', "%$from_number%");
          }
          if($to_number = $request->to_number) {
            $results->where('to_number', 'like', "%$to_number%");
          }
          if($created_at = $request->created_at) {
              $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ? " , $created_at);
          }
           $results = $results->paginate(19);

           $data = [
               'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function viewData(Request $request)
     {
        $id = $request->id;
        if(!$id) return dd('Not allowed to add new');

        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = CallHistory::findOrNew($id);

         return view('admin.'.$this->module.'.view', compact('nav', 'breads', 'item', 'moduleName'));
     }
 }
