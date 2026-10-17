<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\LandingPageForm;
use App\Model\RudrakshaConsultation;

class LandingPageDataController extends Controller
{

    private $module = 'enquiryrudraksh';
    private $moduleName = 'landing-data';
    private $moduleNameSingle = 'landing-data';
     private $modules = 'enquiryrudraksh';
      private $moduleNames = 'Gemstone Landing Page Data';

    public function index(Request $request)
     {
       $slug = $request->slug;
       $nav = $this->module.'-'.$slug;
       if(!adminCan($nav ,'view')) return dd('No Permission');
         $breads = [
             ['name'=>'Enquiry']
         ];
         $moduleName = $this->moduleName;
         return view('admin.landing_data.list', compact('nav','breads', 'moduleName', 'slug'));
     }

     public function all(Request $request){
      $slug = $request->slug;
      $id = $request->id;
      $nav = $this->module.'-'.$slug;
      if($slug == 'rudraksh-landing-page'){
        $pageName = 'Rudraksha Consultation';
      }elseif($slug == 'gemstone-landing-page'){
        $pageName = 'Gemstones Consultation';
      }else{
        $pageName = 'Rudraksha-Consultation';
      }
      $results = RudrakshaConsultation::where('module', 'like', "%$pageName%")->orderBy($request->sort_by?:'created_at',$request->sort_dir?:'desc');

      if($name = $request->name){
        $results->where('fullName', 'like', "%$name%");
      }
      if($phone = $request->phone){
        $results->where('mobile', 'like', "%$phone%");
      }
      if($email = $request->email){
        $results->where('email', 'like', "%$email%");
      }
       if($updated_at_from = $request->updated_at_from) {
           $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to) {
           $results->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
      $results = $results->paginate(16);
      $data = [
        'rows' => view('admin.landing_data.listrows',['results'=>$results, 'module' => $this->module, 'slug', $slug, 'nav'=>$nav])->render(),
        'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
      ];
      return response()->json($data, 200);
     }


     public function form(Request $request)
     {
        $slug = $request->slug;
        $id = $request->id;
        $nav = $this->module.'-'.$slug;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>'Enquiry', 'url'=> route('admin.landing.page.index', [$slug])],
             ['name'=>'Detail']
        ];
         $item = RudrakshaConsultation::find($id);
         if(!$item){
           $item = null;
         }
         return view('admin.landing_data.form', compact('nav', 'breads', 'item','moduleName', 'slug'));
     }

     public function delete(Request $request){
       $slug = $request->slug;
       $nav = $this->module.'-'.$slug;
       if(!adminCan($nav ,'delete')) return dd('No Permission');
        if($id = $request->id) {
            $page = RudrakshaConsultation::find($id);
            $page->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

}
