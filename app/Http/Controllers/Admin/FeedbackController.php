<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ContactEnquiry;

class FeedbackController extends Controller
{
  private $module = 'feedback';
  private $moduleName = 'Feedback';
  private $moduleNameSingle = 'Feedback';

  public function index(Request $request)
   {
      if(!adminCan('feedback','view')) return dd('No Permission');
       $nav = $this->module;
       $breads = [
           ['name'=>$this->moduleName]
       ];
       $moduleName = $this->moduleName;
       $type = "feedback";
       return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName', "type"));
   }

   public function all(Request $request)
   {
        $results = ContactEnquiry::orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

        if($type = $request->type) {
            $results->where('type', 'like', "%$type%");
        }

        if($name = $request->name) {
            $results->where('name', 'like', "%$name%");
        }

        if($email = $request->email) {
          $results->where('email', 'like', "%$email%");
        }

        if($phone = $request->phone) {
          $results->where('phone', 'like', "%$phone%");
        }

        if($created_at = $request->created_at) {
            $results->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ? " , $created_at);
        }

        if($updated_at = $request->updated_at) {
          $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ? " , $updated_at);
        }

         $results = $results->paginate(19);
         $nav = $this->module;
         $data = [
             'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'nav'=>$nav])->render(),
             'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
         ];
         return response()->json($data, 200);
   }

   public function viewData(Request $request)
   {
      $id = $request->id;
      $nav = $this->module;
      $moduleName = $this->moduleNameSingle;
      $breads = [
           ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
           ['name'=>($id ? 'Edit': 'Add')]
      ];
       $item = ContactEnquiry::findOrNew($id);

       return view('admin.'.$this->module.'.view', compact('nav', 'breads', 'item', 'moduleName'));
   }
}
