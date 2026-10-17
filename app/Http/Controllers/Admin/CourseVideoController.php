<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\{Course , CourseVideo };
use App\Http\Controllers\Controller;
class CourseVideoController extends Controller
{
    public function index(Request $request){
        $nav = 'course';
        $breads = [
    ['name'=>'Courses', 'url'=> route('admin.course')],
            ['name'=>'Modules']
        ];
        $course_id = $request->course_id;
        $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');
        return view('admin.courses.coursevideo.list', compact('nav','breads', 'course_id', 'course'));
    }
    public function form(Request $request)
    {
    $course_id = $request->course_id;
    $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');

    $nav = 'course';
        $id = $request->id;
        // dd($request->id);
        $breads = [
            ['name'=>'Courses', 'url'=> route('admin.course')],
            ['name'=>'Modules', 'url'=> route('admin.course.video', $course_id)],
            ['name'=>($id ? 'Edit': 'Add')]
        ];
        $course_module = false;
        if($id) { $course_module = CourseVideo::find($id); }
        // $_status = CourseVideo::statusArray();

        return view('admin.courses.coursevideo.form', compact('nav', 'breads', 'course_module', 'course_id', 'course'));
    }
    public function submit(Request $request) {
    // $count = CourseVideo::count();
    // dd($count);

        $this->validate($request, [
            'title' => 'required|max:255',
            'status' => 'required|in:0,1',
            'coursevideo' => '|file|mimes:mp4,mov,avi,wmv,webm',
        ]);

        $course_id = $request->course_id;
        $id = $request->id;

        if ($id) {
            $courseVideo = CourseVideo::findOrFail($id);
        } else {
            $courseVideo = new CourseVideo();
            $courseVideo->course_id = $course_id;
        }

        $courseVideo->title = $request->title;
        $courseVideo->caption = $request->caption;
        $courseVideo->status = $request->status;

        if ($request->hasFile('coursevideo')) {
            $file = $request->file('coursevideo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('coursevideo'), $filename);
            $courseVideo->file_name = $filename;
        }
        $courseVideo->save();

        return response()->json([
            'message' => 'Course Video Saved Successfully',
            'redirectto' => route('admin.course.video', $course_id)
        ], 200);
    }
    public function all(Request $request)
    {
    $course_id = $request->course_id;
        $videos = CourseVideo::where('course_id', $course_id)->paginate(10);
        $data = [
            'rows' => view('admin.courses.coursevideo.listrows', ['videos' => $videos, 'course_id' => $course_id])->render(),
            'pagination' => view('admin._partials.pagination', ['results' => $videos])->render(),
        ];
        return response()->json($data, 200);
    }
    public function delete(Request $request)
    {
      if($video_id = $request->module_id) {
        $courseModule = CourseVideo::find($video_id);
        $courseModule->delete();
         return response()->json(['message'=>'Course video Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }
}
