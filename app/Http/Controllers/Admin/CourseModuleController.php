<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Course;
use App\Model\CourseModule;
use App\Model\Association;
use App\Model\CourseModuleQuestion;
use App\Model\CourseModuleFile;
use App\Model\CourseVideo;
use App\CourseModuleContent;
use App\CoursePreview;
use Auth;


class CourseModuleController extends Controller
{
    public function index(Request $request)
    {
    $nav = 'course';
        $breads = [
    ['name'=>'Courses', 'url'=> route('admin.course')],
            ['name'=>'Modules']
        ];
        $course_id = $request->course_id;
        $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');
        return view('admin.courses.modules.list', compact('nav','breads', 'course_id', 'course'));
    }

    public function all(Request $request)
    {
     $course_id = $request->course_id;
         // $modules = CourseModule::where('course_id', $course_id)->orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
         $modules = CourseModule::where('course_id', $course_id)->orderBy('order', 'asc');
          if($name = $request->name) {
              $modules->where('name', 'like', "%$name%");
          }

        if(isset($request->status) && is_numeric($request->status)) {
           $modules->where('status', $request->status);
        }
       if($updated_at_from = $request->updated_at_from) {
           $modules->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to) {
           $modules->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
         $modules = $modules->paginate(10);
          $data = [
              'rows' => view('admin.courses.modules.listrows',['modules'=>$modules, 'course_id'=>$course_id])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$modules])->render(),
          ];
          return response()->json($data, 200);
    }

    public function form(Request $request)
    {
    $course_id = $request->course_id;
    $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');

    $nav = 'course';
        $id = $request->id;
    // dd("ok");
        $breads = [
            ['name'=>'Courses', 'url'=> route('admin.course')],
            ['name'=>'Modules', 'url'=> route('admin.course.module', $course_id)],
            ['name'=>($id ? 'Edit': 'Add')]
        ];
        $course_module = false;
        if($id) { $course_module = CourseModule::find($id); }
        $_status = CourseModule::statusArray();

        return view('admin.courses.modules.form', compact('nav', 'breads', 'course_module', '_status',  'course_id', 'course'));
    }

    public function submit(Request $request) {
    $this->validate($request, [
    'name' => 'required|max:255',
    'details' => 'required',
    'total_time' => 'required|numeric|not_in:0', //each question
    // 'questions' => 'required|numeric|not_in:0',
    'minus_marks' => 'required|numeric|not_in:0',
    // 'passing_marks' => 'required|numeric|not_in:0', //default 40%
    // 'attempt_allow' => 'required|numeric|not_in:0',
    'instant_result' => 'required',
    ],[
        'name.required'=>'Name is Required',
        'details.required'=>'Details is Required',
        'total_time.required'=>'Time is Required',
        // 'questions.required'=>'Questions is Required',
        'minus_marks.required'=>'Marks (minimum) is Required',
        // 'passing_marks.required'=>'Marks (passing) is Required',
        // 'attempt_allow.required'=>'Attemps is Required',
        'instant_result.required'=>'Result is Required',
      ]);

    $id = $request->id;
    $course_id = $request->course_id;
    $userid = Auth::guard('admin')->user()->id;
    if($id){
    $courseModule = CourseModule::find($id);
    $courseModule->updated_by 	= $userid;
    }else {
    $courseModule = new CourseModule();
    $courseModule->created_by 	= $userid;
    }
    $courseModule->course_id 				= $request->course_id;
    $courseModule->name 					= $request->name;
    $courseModule->details 					= $request->details;
    $courseModule->order 					= $request->order ? $request->order : 1;
    $courseModule->status 					= $request->status ? $request->status : 0;
    $courseModule->total_time 				= $request->total_time ? $request->total_time : 0;
    $courseModule->questions 				= $request->questions ? $request->questions : 0;
    $courseModule->minus_marks 				= $request->minus_marks ? $request->minus_marks : 0;
    $courseModule->passing_marks 			= $request->passing_marks ? $request->passing_marks : 0;
    $courseModule->attempt_allow 			= $request->attempt_allow ? $request->attempt_allow : 0;
    $courseModule->instant_result 			= $request->instant_result ? $request->instant_result : 0;
    $courseModule->save();

      return response()->json(['message'=>'Course Module Saved Successfully', 'redirectto'=>route('admin.course.module.edit', [$course_id, $courseModule->id])], 200);
    }

      public function delete(Request $request)
    {
      if($module_id = $request->id) {
        $courseModule = CourseModule::find($module_id);
        $courseModule->delete();
         return response()->json(['message'=>'Course Module Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function duplicate(Request $request){
      if($id = $request->course_id) {
        $moduleId = $request->id;
        $courseModule = CourseModule::where('id', $moduleId)->where('course_id', $id)->get();
        foreach($courseModule as $cm){
          $ncm = $cm->replicate();
          $ncm->course_id = $id;
          $ncm->name = $cm->name.' - Cloned';
          $ncm->save();

          $courseModuleContent = CourseModuleContent::where('course_id', $id)->where('module_id', $cm->id)->get();
          foreach($courseModuleContent as $cmc){
              $ncmc = $cmc->replicate();
              $ncmc->course_id = $id;
              $ncmc->module_id = $ncm->id;
              $ncmc->save();

              $courseModuleQuestion = CourseModuleQuestion::where('content_id', $cmc->id)->where('course_id', $id)->where('module_id', $cm->id)->get();
              foreach($courseModuleQuestion as $cmq){
                $ncmq = $cmq->replicate();
                $ncmq->course_id = $id;
                $ncmq->module_id = $ncm->id;
                $ncmq->content_id = $ncmc->id;
                $ncmq->save();
              }

              $courseModuleFile = CourseModuleFile::where('content_id', $cmc->id)->get();
              foreach($courseModuleFile as $cmf){
                $ncmf = $cmf->replicate();
                $ncmf->content_id = $ncmc->id;
                $ncmf->save();
              }

              $courseVideo = CourseVideo::where('content_id', $cmc->id)->get();
              foreach($courseVideo as $cv){
                $ncv = $cv->replicate();
                $ncv->content_id = $ncmc->id;
                $ncv->save();
              }

              $coursePreview = CoursePreview::where('content_id', $cmc->id)->get();
              foreach($coursePreview as $cp){
                $ncp = $cp->replicate();
                $ncp->content_id = $ncmc->id;
                $ncp->save();
              }
          }
        }
         return response()->json(['message'=>'Course Module Cloned successfully!'], 200);
      }
      return response()->json(['message'=>'Error in cloning data'], 401);
    }
}
