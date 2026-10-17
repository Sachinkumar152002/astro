<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\EnquiryCmsTwo;

class EnquiryCmsTwoController extends Controller
{
    private $module = 'enquirycmstwo';
    private $moduleName = 'NG Software';
    private $moduleNameSingle = 'NG Software';

    public function index(Request $request){
        if(!adminCan($this->module, 'view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>'Enquiry']
         ];
         $moduleName = $this->moduleName;
         return view('admin.'.$this->module.'.list', compact('nav', 'breads', 'moduleName'));
     }

     public function all(Request $request)
     {
        $results = EnquiryCmsTwo::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
        $nav=$this->module;

        if($name = $request->name){
          $results->where('name', 'like', "%$name%");
        }
        if($phone = $request->phone){
          $results->where('phone', 'like', "%$phone%");
        }
        if($email = $request->email){
          $results->where('email', 'like', "%$email%");
        }
        if($updatd_at_from = $request->updatd_at_from) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updatd_at_from);
        }
        if($updatd_at_to = $request->updatd_at_to) {
            $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updatd_at_to);
        }
        $results = $results->paginate(19);
        $data = [
          'rows' => view('admin.'.$this->module.'.listrows',['results'=>$results, 'nav'=>$nav])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
         ];
         return response()->json($data, 200);
     }

     public function viewData(Request $request){
       $id = $request->id;
       $nav = $this->module;
       $moduleName = $this->moduleNameSingle;
       $breads = [
         ['name'=>'Enquiry', 'url'=> route('admin.'.$this->module.'.index')],
         ['name'=>($id ? 'Edit': 'Add')]
       ];
       $item = EnquiryCmsTwo::find($id);
       if(!$item){
         return 'Hoshyari nahi mere bete.';
       }
       return view('admin.'.$this->module.'.view', compact('nav', 'breads', 'item', 'moduleName'));
     }

     public function delete(Request $request){
        if(!adminCan($this->module, 'delete')) return dd('No Permission');
        if($id = $request->id){
          $item = EnquiryCmsTwo::find($id);
          $item->delete();
          return response()->json(['message'=>'Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
}
