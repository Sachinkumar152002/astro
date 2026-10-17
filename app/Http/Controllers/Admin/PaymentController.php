<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\RudrakshaConsultation;

class PaymentController extends Controller
{
    private $module = 'payment';
    private $moduleName = 'Rudraksha Consultation';
    private $moduleNameSingle = 'Contact Enquery';

    public function index(Request $request)
     {
         $nav = $this->module;
         $breads = [
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         //dd($nav, $breads, $moduleName);
         return view('admin.'.$this->module.'.list', compact('nav','breads', 'moduleName'));
     }

     public function all(Request $request)
     {
        $results = RudrakshaConsultation::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');

        if($module = $request->module) {
            $results->where('module', 'like', "%$module%");
        }
        if($name = $request->name) {
            $results->where('fullName', 'like', "%$name%");
        }
        if($phone = $request->phone) {
          $results->where('mobile', 'like', "%$phone%");
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
