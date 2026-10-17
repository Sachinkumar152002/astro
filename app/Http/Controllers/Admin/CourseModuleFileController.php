<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Course;
use App\Model\CourseModule;
use App\Model\CourseModuleFile;
use App\Model\Association;
use Auth;
use Zipper;
use File;

class CourseModuleFileController extends Controller
{
     public function index(Request $request)
    {

        $course_id = $request->course_id;
        $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');

        $module_id = $request->module_id;
        $module = CourseModule::find($module_id);
        if(!$module) return redirect()->route('admin.course.module', [$course_id]);

    $nav = 'course';
        $breads = [
    ['name'=>'Courses', 'url'=> route('admin.course')],
    ['name'=>'Modules', 'url'=> route('admin.course.module', [$course_id]) ],
            ['name'=>'Files']
        ];

        return view('admin.courses.modules.files.list', compact('nav','breads', 'course_id', 'course', 'module_id', 'module') );
    }

    public function all(Request $request)
    {
     $course_id = $request->course_id;
     $module_id = $request->module_id;
         $moduleFile = CourseModuleFile::where('course_id', $course_id)->where('module_id', $module_id)->paginate(10);

          $data = [
              'rows' => view('admin.courses.modules.files.listrows',['files'=>$moduleFile, 'course_id'=>$course_id, 'module_id'=>$module_id])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$moduleFile])->render(),
          ];

          return response()->json($data, 200);
    }

    public function form(Request $request)
    {
    $course_id = $request->course_id;
        $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');

        $module_id = $request->module_id;
        $module = CourseModule::find($module_id);
        if(!$course) return redirect()->route('admin.course.module', [$course_id]);

        $id = $request->id;
        $nav = 'course';
        $breads = [
    ['name'=>'Courses', 'url'=> route('admin.course')],
    ['name'=>'Modules', 'url'=> route('admin.course.module', [$course_id])],
            ['name'=>'Files']
        ];

        $course_module_file = false;
        if($id) { $course_module_file = CourseModuleFile::find($id); }
        $_status = CourseModuleFile::statusArray();
        $_types  = CourseModuleFile::typesArray();


        return view('admin.courses.modules.files.form', compact('nav', 'breads', 'course_module_file', '_status',  'course_id', 'course', 'module_id', 'module', '_types'));
    }

    public function submit(Request $request) {

    $id = $request->id;

    if(!$id) {
    // $this->validate($request, [
        // 		'file' => 'required'
    // ]);
    }

    $course_id = $request->course_id;
    $module_id = $request->module_id;
    $userid = Auth::guard('admin')->user()->id;
    if($id){
    $courseModuleFile = CourseModuleFile::find($id);
    $courseModuleFile->updated_by 	= $userid;
    }else {
    $courseModuleFile = new CourseModuleFile();
    $courseModuleFile->created_by 	= $userid;

    }
    $courseModuleFile->course_id 				= $request->course_id;
        $courseModuleFile->module_id                = $request->module_id;
    $courseModuleFile->title 				= $request->title;
    $courseModuleFile->order 					= $request->order ? $request->order : 0;
    $courseModuleFile->status 					= $request->status ? $request->status : 0;
    $courseModuleFile->video_url 				= $request->video_url;

    $courseModuleFile->save();

    if(!$id && $request->file){
            $file = json_decode($request->file);

    $fileId = $courseModuleFile->id;

    $filePath = public_path('file');
    $filetype = $request->file_type;

            if($file->file_type=='wbt') {
                $fileDestination = $filePath.'/'.$fileId;
               if(!File::exists($fileDestination)) {
                    File::makeDirectory($fileDestination, 0755, true);
                }
                $fileName = $file->file_name;
                $fileUpload = public_path('file/zipupload');
                $zipper = new \Chumper\Zipper\Zipper;
    $ZipFile = $zipper->make($fileUpload.'/'.$fileName)->extractTo($fileDestination);
                $zipper->close();
                $folderName = str_replace('.zip', '', $file->original_name);
                if(file_exists($fileDestination.'/'.$folderName.'/index.html')) {
                    $fileName = $fileId.'/'.$folderName.'/index.html';
                }elseif(file_exists($fileDestination.'/'.$folderName.'/presentation.html')) {
                    $fileName = $fileId.'/'.$folderName.'/presentation.html';
                } else {
                    $fileName = $fileId.'/'.$folderName;
                }

            } else {
                $fileName = $file->folder.'/'.$file->file_name;
            }

    $courseModuleFile->file 					= $fileName;
    $courseModuleFile->file_type 				= $file->file_type;
    $courseModuleFile->file_orginal_name 		= $file->original_name;
    $courseModuleFile->attributes 				= $request->file;
    $courseModuleFile->save();
    }

    return response()->json(['message'=>'Course Module File Saved Successfully', 'redirectto'=>route('admin.course.module.file', [$course_id, $module_id])], 200);

    return $request;
    }

      public function delete(Request $request)
    {
      if($file_id = $request->file_id) {
        $CourseModuleFile = CourseModuleFile::find($file_id);
        $CourseModuleFile->delete();
         return response()->json(['message'=>'Course Module File Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:pdf,zip'
        ]);

        $filePath = public_path('file');
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0755, true);
        }
        $time = time();
        $fileName = $time.'.'.$request->file->getClientOriginalExtension();

        $fileDestination = $filePath.'/'. $time;
        if(!File::exists($fileDestination)) {
            File::makeDirectory($fileDestination, 0755, true);
        }
        $filetype = '';
        if($request->file->getClientOriginalExtension()=='zip') {
            $fileUpload = public_path('file/zipupload');
            if(!File::exists($fileUpload)) {
                File::makeDirectory($fileUpload, 0755, true);
            }
            $request->file->move($fileUpload, $fileName);
            // $ZipFile = Zipper::make($fileUpload.$fileName)->extractTo($fileDestination);
            $filetype = 'wbt';
        } else {
            $request->file->move($fileDestination, $fileName);
            $filetype = 'pdf';
        }

        return response()->json([
                'file_type'=>$filetype,
                'folder' => $time,
                'file_name' => $fileName,
                'original_name'=> $request->file->getClientOriginalName()
        ], 200);
    }
}
