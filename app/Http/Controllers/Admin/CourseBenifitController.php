<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Model\{Course , CourseVideo };
use App\{CourseBenifits};
use Illuminate\Http\Request;
use App\Model\Media;

class CourseBenifitController extends Controller
{

      public function index(Request $request){
          $nav = 'course';
          $breads = [
    ['name'=>'Courses', 'url'=> route('admin.course')],
              ['name'=>'Course Benefits']
          ];
          $course_id = $request->course_id;
          $course = Course::find($course_id);
          if(!$course) return redirect()->route('admin.course');
          return view('admin.courses.coursebenifit.list', compact('nav','breads', 'course_id', 'course'));
      }
      public function form(Request $request)
      {
        // dd($request->all());
    $course_id = $request->course_id;
    $course = Course::find($course_id);
          if(!$course) return redirect()->route('admin.course');
    $nav = 'course';
          $id = $request->id;
          // dd($request->id);
          $breads = [
              ['name'=>'Courses', 'url'=> route('admin.course')],
              ['name'=>'Modules', 'url'=> route('admin.course.benifit', $course_id)],
              ['name'=>($id ? 'Edit': 'Add')]
          ];
          $course_module = false;
           $medias  = Media::where('type','courseBenifits')->get();
          if($id) { $course_module = CourseBenifits::find($id); }
          // $_status = CourseVideo::statusArray();

          return view('admin.courses.coursebenifit.form', compact('nav', 'breads', 'course_module', 'course_id', 'course','medias'));
      }
      public function submit(Request $request) {

          $this->validate($request, [
              'title' => 'required|max:255',
              'description' => 'required',
              'image_id' => 'required|numeric|not_in:0'
          ],[
            'title.required'=>'Title is Required',
            'description.required'=>'Title is Required',
            'image_id.required'=>'Image is Required',
          ]);

          $course_id = $request->course_id;
          $id = $request->id;

          if ($id) {
              $courseVideo = CourseBenifits::findOrFail($id);
          } else {
              $courseVideo = new CourseBenifits();
              $courseVideo->course_id = $course_id;
          }

          $courseVideo->title = $request->title;
          $courseVideo->description = $request->description;
          $courseVideo->status = $request->status ? $request->status : 0;
          $courseVideo->image_id 		= $request->image_id?:0;

          $courseVideo->save();

          return response()->json([
              'message' => 'Course benifits Saved Successfully',
              'redirectto' => route('admin.course.benifit', $course_id)
          ], 200);
      }
    public function all(Request $request)
      {
    $course_id = $request->course_id;
        $videos = CourseBenifits::where('course_id', $course_id)->orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
         if($title = $request->title) {
             $videos->where('title', 'like', "%$title%");
         }
        if(isset($request->status) && is_numeric($request->status)) {
           $videos->where('status', $request->status);
        }
        if($updated_at_from = $request->updated_at_from) {
            $videos->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
        }
        if($updated_at_to = $request->updated_at_to) {
            $videos->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
        }
        $videos = $videos->paginate(10);
        $data = [
            'rows' => view('admin.courses.coursebenifit.listrows', ['videos' => $videos, 'course_id' => $course_id])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $videos])->render(),
        ];
          return response()->json($data, 200);
      }
    public function delete(Request $request)
      {
        if($video_id = $request->id) {
          $courseModule = CourseBenifits::find($video_id);
          $courseModule->delete();
           return response()->json(['message'=>'Course benifit Deleted successfully!'], 200);
        }
        return response()->json(['message'=>'Error in deleting data'], 401);
      }

      public function duplicate(Request $request){
        if($id = $request->course_id) {
          $cb_id = $request->id;
          $courseBenifits = CourseBenifits::where('id', $cb_id)->where('course_id', $id)->get();
          foreach($courseBenifits as $cb){
            $ncb = $cb->replicate();
            $ncb->course_id = $id;
            $ncb->title = $cb->title.' - Cloned';
            $ncb->save();
          }
           return response()->json(['message'=>'Course Benefits Cloned successfully!'], 200);
        }
        return response()->json(['message'=>'Error in cloning data'], 401);
      }
}
