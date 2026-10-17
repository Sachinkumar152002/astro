<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{CourseModuleContent};
use App\Model\Course;
use App\Model\CourseModule;

class CourseModuleContentController extends Controller
{
    private $module = 'Module Content';
    private $moduleName = 'Videos';
    private $moduleNameSingle = 'Video';

    public function index(Request $request)
    {
        $course_id = $request->course_id;
        $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');

        $module_id = $request->module_id;
        $module = CourseModule::find($module_id);
        if(!$module) return redirect()->route('admin.course.module', [$course_id]);

    $nav = 'content';
        $breads = [
    ['name'=>'Courses', 'url'=> route('admin.course')],
    ['name'=>'Modules', 'url'=> route('admin.course.module', [$course_id]) ],
            ['name'=>'Content']
        ];

        return view('admin.courses.modules.content.list', compact('nav','breads', 'course_id', 'course', 'module_id', 'module') );
    }

    public function all(Request $request)
    {
     $course_id = $request->course_id;
     $module_id = $request->module_id;
       $contents = CourseModuleContent::where('course_id', $course_id)->where('module_id', $module_id)->paginate(10);
          $data = [
              'rows' => view('admin.courses.modules.content.listrows',['contents'=>$contents, 'course_id'=>$course_id, 'module_id'=>$module_id])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$contents])->render(),
          ];

          return response()->json($data, 200);
    }



    //
    public function form(Request $request)
    {
        $course_id = $request->course_id;
        $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');

        $module_id = $request->module_id;
        $module = CourseModule::find($module_id);
        if(!$course) return redirect()->route('admin.course.module', [$course_id]);
        //
       $id = $request->id;
        $nav = $this->module;
        $moduleName = $this->moduleNameSingle;
        $breads = [
    ['name'=>'Courses', 'url'=> route('admin.course')],
    ['name'=>'Modules', 'url'=> route('admin.course.module', [$course_id])],
            ['name'=>'Preview']
        ];
        $item = false;
        $item = Video::findOrNew($id);
        return view('admin.courses.modules.preview.form', compact('nav', 'breads', 'item', 'moduleName','course_id','module_id'));
    }
    //

    public function submit(Request $request) {
        // dd($request->all());
        $id = $request->id;
         $this->validate($request, [
             'title' => 'required|max:255',
             'course_id'=>'required',
             'course_module_id'=> 'required',
             'video_url' => 'required',
             'video_id' => 'required',
             'thumbnail' => 'required',
             'category' => 'required',
         ]);

         $id = $request->id;

        $video = CoursePreview::findOrNew($id);
        $video->course_id = $request->course_id;
        $video->course_module_id = $request->course_module_id;
        $video->video_url = $request->video_url;
        $video->video_id = $request->video_id;
        $video->title = $request->title;
        $video->thumbnail = $request->thumbnail;
        $video->description = $request->description;
        $video->category = $request->category;
        $video->save();

        return response()->json([
            'message' => 'Course preview Module Preview Saved Successfully',
            'redirectto' => route('admin.course.module.preview', ['course_id'=>$request->course_id, 'module_id'=>$request->course_module_id])
        ], 500);
    }


      public function delete(Request $request)
    {
      if($question_id = $request->question_id) {
        $CourseModuleQuestion = CoursePreview::find($question_id);
        $CourseModuleQuestion->delete();
         return response()->json(['message'=>'Course Module Question Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }
}
