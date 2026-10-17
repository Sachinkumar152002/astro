<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ContactEnquiry;

class EnquiriesController extends Controller
{
    private $module = 'enquiry';
    private $moduleName = 'Contact Enquiries';
    private $moduleNameSingle = 'Contact Enquery';

    public function index(Request $request)
     {
        if(!adminCan('contact-enquiry','view')) return dd('No Permission');
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         $type = "enquiry";
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName', "type"));
     }

     public function all(Request $request)
     {
        $results = ContactEnquiry::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');

        if($type = $request->type) {
            $results->where('type', 'like', "%$type%");
        }

        if($name = $request->name) {
            $results->where('name', 'like', "%$name%");
        }
        if($phone = $request->phone) {
          $results->where('phone', 'like', "%$phone%");
        }
        if($email = $request->email) {
          $results->where('email', 'like', "%$email%");
        }
        if($message = $request->message) {
          $results->where('message', 'like', "%$message%");
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
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>$this->moduleName, 'url'=> route('admin.'.$this->module.'.index')],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = ContactEnquiry::findOrNew($id);

         return view('admin.'.$this->module.'.view', compact('nav', 'breads', 'item', 'moduleName'));
     }

     public function delete(Request $request)
     {
        if(!adminCan('contact-enquiry','delete')) return dd('No Permission');
        if($id = $request->id) {
          $item = ContactEnquiry::find($id);
          $item->delete();
          return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }
 }
