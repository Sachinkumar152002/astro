<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{CourseModuleContent, CoursePreview};
use App\Model\{Course, CourseModule, CourseVideo, CourseModuleFile};
use App\Model\CourseModuleQuestion;
use Zipper;
use File;

class CourseModuleContentController extends Controller
{
    private $module = 'Module Content';
    private $moduleName = 'Videos';
    private $moduleNameSingle = 'Video';

    public function index(Request $request)
    {
      // return $request->sidebar;
        $course_id = $request->course_id;
        $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');

        $module_id = $request->module_id;
        $module = CourseModule::find($module_id);
        if(!$module) return redirect()->route('admin.course.module', [$course_id]);

    $nav = 'content';
        // $breads = [
        // 	['name'=>'Courses', 'url'=> route('admin.course')],
        // 	['name'=>'Modules', 'url'=> route('admin.course.module', [$course_id]) ],
        //     ['name'=>'Content']
        // ];
        $breads = [
    ['name'=>'Modules', 'url'=> '' ],
          ['name'=>'Content', 'url'=> route('admin.course.module.content', [$course_id, $module_id])],
        ];

        return view('admin.courses.modules.content.list', compact('nav','breads', 'course_id', 'course', 'module_id', 'module') );
    }

    public function all(Request $request)
    {
      $course_id = $request->course_id;
      $module_id = $request->module_id;
      $contents = CourseModuleContent::where('course_id', $course_id)->where('module_id', $module_id)->orderBy('order', 'asc');
       if($title = $request->title) {
           $contents->where('title', 'like', "%$title%");
       }
       // if(isset($request->status) && is_numeric($request->status)) {
       //    $contents->where('status', $request->status);
       // }
       if($updated_at_from = $request->updated_at_from) {
           $contents->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to) {
           $contents->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
       $contents = $contents->paginate(10);
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
        // $breads = [
        // 	['name'=>'Courses', 'url'=> route('admin.course')],
        // 	['name'=>'Modules', 'url'=> route('admin.course.module', [$course_id])],
        //     ['name'=>'Content']
        // ];
        $breads = [
    ['name'=>'Modules', 'url'=> '' ],
          ['name'=>'Content', 'url'=> route('admin.course.module.content', [$course_id, $module_id])],
        ];
        $content = false;
        if($id){
          $content = CourseModuleContent::find($id);
        }
        return view('admin.courses.modules.content.form', compact('nav', 'breads', 'content', 'moduleName','course_id','module_id'));
    }
    //

    public function submit(Request $request) {
      // dd($request->description);
        $id = $request->id;
         $this->validate($request, [
             'title' => 'required|max:255',
             'description' => 'required',
             'preview_type'=>'required',
             'video_type'=> 'required',
         ],[
             'title.required' => 'Title is Required',
             'description.required' => 'Description is Required',
             'preview_type.required'=>'Preview Type is Required',
             'video_type.required'=> 'Video Type is Required',
         ]);
         if($request->preview_type == 'vimeo'){
           $this->validate($request, [
               'preview_other_video_url' => 'required',
           ],[
             'preview_other_video_url.required'=>'This Preview Video Url is required'
           ]);
         }elseif($request->preview_type == 'youtube'){
           $this->validate($request, [
               // 'preview_youtube_video_url' => 'required',
               // 'preview_video_id' => 'required',
               // 'preview_title' => 'required',
               // 'preview_thumbnail' => 'required',
               // 'preview_description' => 'required',
           ],[
             'preview_youtube_video_url.required'=>'This Preview Youtube Video Url is required'
           ]);
         }elseif($request->preview_type == 'upload'){
           $this->validate($request, [
               'preview_video_url1' => 'required',
           ],[
             'preview_video_url1.required'=>'This Preview File is required'
           ]);
         }

        if($request->video_type == 'vimeo'){
          $this->validate($request, [
              // 'video_other_video_url' => 'required',
          ],[
            'video_other_video_url.required'=>'This Video Urlis required'
          ]);
        }elseif($request->video_type == 'youtube'){
          $this->validate($request, [
              // 'video_youtube_video_url' => 'required',
              // 'video_video_id' => 'required',
              // 'video_title' => 'required',
              // 'video_thumbnail' => 'required',
              // 'video_description' => 'required',
          ],[
            'video_youtube_video_url.required'=>'This Video Youtube Video Url is required'
          ]);
        }
        $id = $request->id;
        $content = CourseModuleContent::findOrNew($id);
        $content->course_id = $request->course_id;
        $content->module_id = $request->module_id;
        $content->title = $request->title;
        $content->description = $request->description;
        $content->status = $request->status?:0;
        $content->order = $request->order?:1;
        $content->save();
        $preview = CoursePreview::where('content_id', $content->id)->first();
        if(!$preview){
          $preview = new CoursePreview();
          $preview->content_id = $content->id;
          $preview->category = 'daily';
        }
        $preview->type = $request->preview_type;
        $preview->video_url = $request->preview_other_video_url;
       if($request->preview_type == 'vimeo'){
         $video_id = $request->preview_video_id ;
         $video_hash = '';
         if($video_id){
           $preview->video_url = $request->preview_other_video_url;
           $preview->video_id = $request->preview_video_id;
           $preview->title = $request->preview_title;
           $preview->thumbnail = $request->preview_thumbnail;
           $preview->description = $request->preview_description;
         } else {
           if(str_contains($request->preview_other_video_url, 'vimeo.com/')){
             $video_id = explode('/', $request->preview_other_video_url);

             if(count($video_id)==5){
               $video_id = $video_id[3];
               $video_hash = $video_id[4];
             }elseif(count($video_id)==4){
               $video_id = $video_id[3];
             }
           }
         }
         $returnArray = vimeoVideo($video_id);
         if($returnArray['video_hash'] && $returnArray['player_embed_url']){
           $preview->title = $preview->title  ? : $returnArray['title'];
           $preview->description = $preview->description ? : $returnArray['description'];
           $preview->video_id = $preview->video_id ? : $returnArray['video_id'];
           $preview->video_hash = $returnArray['video_hash'];
           $preview->player_embed_url = $returnArray['player_embed_url'];
           $preview->thumbnail = $preview->thumbnail ? : 'vimeo';
         }
          $preview->save();
       }elseif($request->preview_type == 'youtube'){
         $preview->video_url = $request->preview_youtube_video_url;
         $preview->video_id = $request->preview_video_id;
         $preview->title = $request->preview_title;
         $preview->thumbnail = $request->preview_thumbnail;
         $preview->description = $request->preview_description;
         $preview->save();
       }

       $video = CourseVideo::where('content_id', $content->id)->first();
       if(!$video){
         $video = new CourseVideo();
         $video->content_id = $content->id;
       }
       $video->type = $request->video_type;
       $video->video_url = $request->video_other_video_url;
       if($request->video_type == 'vimeo'){

           $video_id = $request->video_video_id ;
           $video_hash = '';
           if($video_id){
             $video->video_url = $request->video_other_video_url;
             $video->video_id = $request->video_video_id;
             $video->title = $request->video_title;
             $video->thumbnail = $request->video_thumbnail;
             $video->description = $request->video_description;
           } else {
             if(str_contains($request->video_other_video_url, 'vimeo.com/')){
               $video_id = explode('/', $request->video_other_video_url);

               if(count($video_id)==5){
                 $video_id = $video_id[3];
                 $video_hash = $video_id[4];
               }elseif(count($video_id)==4){
                 $video_id = $video_id[3];
               }
             }
           }
           $returnArray = vimeoVideo($video_id);
           if($returnArray['video_hash'] && $returnArray['player_embed_url']){
             $video->title = $video->title  ? : $returnArray['title'];
             $video->description = $video->description ? : $returnArray['description'];
             $video->video_id = $video->video_id ? : $returnArray['video_id'];
             $video->video_hash = $returnArray['video_hash'];
             $video->player_embed_url = $returnArray['player_embed_url'];
             $video->thumbnail = $video->thumbnail ? : 'vimeo';
           }
            $video->save();
       }elseif($request->video_type == 'youtube'){
         $video->video_url = $request->video_youtube_video_url;
         $video->video_id = $request->video_video_id;
         $video->title = $request->video_title;
         $video->thumbnail = $request->video_thumbnail;
         $video->description = $request->video_description;
         $video->save();
       }
      if($request->file_name){
        $file = CourseModuleFile::where('content_id', $content->id)->first();
        if(!$file){
          $file = new CourseModuleFile();
          $file->content_id = $content->id;
        }
        $file->file = $request->file_orginal_name;
        $file->title = $request->file_folder;
        $file->file_type = $request->file_type;
        $file->file_orginal_name = $request->file_name;
        $file->save();
      }

      return response()->json([
          'message' => 'Course preview Module Preview Saved Successfully',
          'redirectto' => route('admin.course.module.content', ['course_id'=>$request->course_id, 'module_id'=>$request->module_id])
      ], 200);
    }

    public function upload(Request $request)
    {
    // dd($request->module_id);
        $this->validate($request, [
            'file' => 'required|mimes:pdf,zip'
        ]);

        $filePath = public_path('course');
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true);
        }

        $filePath = public_path('course/'.$request->course_id);
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true);
        }

        $filePath = public_path('course/'.$request->course_id.'/'.$request->module_id);
        if(!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true);
        }
        $time = time();
        $fileName = $time.'.'.$request->file->getClientOriginalExtension();

        $fileDestination = $filePath.'/'. $time;
        if(!File::exists($fileDestination)) {
            File::makeDirectory($fileDestination, 0755, true);
        }
        $filetype = '';
        if($request->file->getClientOriginalExtension()=='zip') {
            // $fileUpload = public_path('file/zipupload');
            // $fileUpload = $fileDestination;
            // if(!File::exists($fileUpload)) {
            //     File::makeDirectory($fileUpload, 0755, true);
            // }
            $request->file->move($fileDestination, $fileName);
            // $ZipFile = Zipper::make($fileUpload.$fileName)->extractTo($fileDestination);
            $filetype = 'zip';
        } else {
            $request->file->move($fileDestination, $fileName);
            $filetype = 'pdf';
        }

        return response()->json([
                'file_type'=>$filetype,
                'folder' => $time,
                'file_name' => $fileName,
                'original_name'=> $request->file->getClientOriginalName(),
                'fileUpload'=> $fileDestination
        ], 200);
    }


    public function delete(Request $request){
      // return $request->id;
      if($content_id = $request->id) {
        $content = CourseModuleContent::find($content_id);
        $content->delete();
        $preview = CoursePreview::where('content_id', $content_id);
        $preview->delete();
        $video = CourseVideo::where('content_id', $content_id);
        $video->delete();
        $file = CourseModuleFile::where('content_id', $content_id);
        $file->delete();
         return response()->json(['message'=>'Course Module Question Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function duplicate(Request $request){
      if($id = $request->course_id) {

          $module_id = $request->module_id;
          $cmcId = $request->id;

          $courseModuleContent = CourseModuleContent::where('id', $cmcId)->where('course_id', $id)->where('module_id', $module_id)->get();
          foreach($courseModuleContent as $cmc){
              $ncmc = $cmc->replicate();
              $ncmc->course_id = $id;
              $ncmc->module_id = $module_id;
              $ncmc->title = $cmc->title.' - Cloned';
              $ncmc->save();

              $courseModuleQuestion = CourseModuleQuestion::where('content_id', $cmc->id)->where('course_id', $id)->where('module_id', $module_id)->get();
              foreach($courseModuleQuestion as $cmq){
                $ncmq = $cmq->replicate();
                $ncmq->course_id = $id;
                $ncmq->module_id = $module_id;
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


         return response()->json(['message'=>'Course Module Content Cloned successfully!'], 200);
      }
      return response()->json(['message'=>'Error in cloning data'], 401);
    }


}
