<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\LmsCourse;
use App\User;
use App\Model\Course;
use App\Model\CourseExploreModule;

class LmsCourseController extends Controller
{
    private $module = 'user_lms';
    private $moduleName = 'Lms';
    private $moduleNameSingle = 'LMS';

    public function index(Request $request)
     {
         $nav = $this->module;
         $breads = [
              ['name'=>'Cusotmer', 'url'=> route('admin.customer.index')],
             ['name'=>$this->moduleName]
         ];
         $moduleName = $this->moduleName;
         $user_id = $request->user_id;
         return view('admin.customer.lms.list', compact('nav','breads', 'moduleName', 'user_id'));
     }

     public function all(Request $request)
     {
       $user_id = $request->user_id;
          $results = LmsCourse::orderBy('id', 'desc')->where('user_id', $user_id);

          if($course_name = $request->course_name) {
              $results->where('course_name', 'like', "%$course_name%");
          }
         if($expire_date_from = $request->expire_date_from) {
             $results->whereRaw("DATE_FORMAT(expire_date, '%Y-%m-%d') >= ? " , $expire_date_from);
         }
         if($expire_date_to = $request->expire_date_to) {
             $results->whereRaw("DATE_FORMAT(expire_date, '%Y-%m-%d') <= ? " , $expire_date_to);
         }
          if(is_numeric($request->status)) {
            $results->where('status', $request->status);
          }
           $results = $results->paginate(13);

           $data = [
               'rows' => view('admin.customer.lms.listrows',['results'=>$results, 'user_id'=>$user_id])->render(),
               'pagination' => view('admin._partials.pagination',['results'=>$results])->render(),
           ];
           return response()->json($data, 200);
     }


     public function form(Request $request)
     {
        $id = $request->id;
        $user_id = $request->user_id;
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
             ['name'=>'Customer', 'url'=> route('admin.customer.index')],
             ['name'=>$this->moduleName, 'url'=> route('admin.customer.course', [$request->user_id])],
             ['name'=>($id ? 'Edit': 'Add')]
        ];
         $item = LmsCourse::find($id);
         $course = null;
         $courses = Course::where('status', 1)->pluck('name', 'id')->toArray();
         if(!$item){
           $item = null;
         }else{
           $course = $item->course;
         }
         return view('admin.customer.lms.form', compact('nav', 'breads', 'item', 'moduleName', 'user_id', 'courses', 'course'));
     }

     public function submit(Request $request) {
        $id = $request->id;
        $user_id = $request->user_id;


         $this->validate($request, [
           'course_id' => 'required|numeric|not_in:0',
            'expire_date' => 'required|date',
         ],[
           'course_id.required' => 'Course is Required',
           'expire_date.required' => 'Expiry date is Required',
         ]);
         $user = User::find($user_id);
         $course = Course::find($request->course_id);

        $item = LmsCourse::findOrNew($id);
        $item->user_id 		= $user_id;
        $item->user_type 		= 'user';
        $item->user_name 		= $user->name;
        $item->user_email 		= $user->email;
        $item->user_phone 		= $user->phone;
        $item->course_id 		= $request->course_id;
        $item->course_name 		= $course->name;
        if($course->special_price && date('Y-m-d') >= $course->special_from_date && date('Y-m-d') <= $course->special_from_to ){
          $item->amount = $course->special_price;
        }else{
          $item->amount = $course->price;
        }
        $item->expire_date 		= date('Y-m-d', strtotime($request->expire_date));
        $item->status 		= $request->status?:0;
        $item->save();
        $redirectTo =route('admin.customer.course.edit', [$user_id, $item->id]);
        return response()->json(['message'=>$this->moduleNameSingle.' Saved Successfully', 'redirectto'=>$redirectTo], 200);
     }

     public function delete(Request $request)
     {
        if($id = $request->id) {
            $item = LmsCourse::find($id);
            $item->delete();
            return response()->json(['message'=>$this->moduleNameSingle.' Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
     }

     public function duplicate(Request $request){
    if($id = $request->id) {
    $item = LmsCourse::find($id);
    $nitem = $item->replicate();
          $nitem->save();
    return response()->json(['message'=>' Cloned successfully!'], 200);
    }
    return response()->json(['message'=>'Error in Cloning data'], 401);
    }

     public function passCourseAndGenerateCertificate(Request $request){
       $id = $request->id;
       $lms=LmsCourse::find($id);
       $course_id = $lms->course_id;
       $course=Course::find($course_id);
       $user_id = $lms->user_id;
       $user_type = $lms->user_type;
       foreach($course->modules as $key => $module){
         $cEM = CourseExploreModule::where('user_id', $user_id)->where('user_type', $user_type)->where('course_id', $course_id)->where('module_id', $module->id)->first();
         if(!$cEM){
           $cEM = new CourseExploreModule();
           $cEM->user_id = $user_id;
           $cEM->user_type = $user_type;
           $cEM->course_id = $course_id;
           $cEM->module_id = $module->id;
         }
         $cEM->passed = 1;
         $cEM->save();
       }
       // if($course){
         // $user = User::find($user_id);
         // $url = downloadCertificate($user, $user_type, $course);
         // return redirect($url);
       // }
       return redirect()->back();
    }

     public function downloadCertificate(Request $request){
       $id = $request->id;
       $lms=LmsCourse::find($id);
       $course_id = $lms->course_id;
       $course=Course::find($course_id);
       $user_id = $lms->user_id;
       $user_type = $lms->user_type;
       if($course){
         $user = User::find($user_id);
         $url = downloadCertificate($user, $user_type, $course);
         return redirect($url);
       }
       return redirect()->back();
    }
}
