<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\{Course,Media,Association, Astrologer, CourseModuleFile, CourseModuleQuestion, CourseVideo, CourseModule, HsnCode};
use App\{CourseCategory,Instructor,CourseDay,Specification,PreviewVideo,User, CourseBenifits, CourseModuleContent, CoursePreview};
use Auth;
use App\CourseReview;
use \Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use File;


class CourseController extends Controller
{
    public function index(Request $request)
    {
      if(!adminCan('course', 'view')) return dd('No Permission');
    $nav = 'course';
        $breads = [
            ['name'=>'Courses']
        ];
        return view('admin.courses.list', compact('nav','breads'));
    }

    public function courseReview(Request $request){
      if(!adminCan('course-review', 'view')) return dd('No Permission');
      $nav = 'courseReview';
      $breads = [
        ['name' => 'Review', 'url'=> route('admin.course.review')],
      ];
      return view('admin.coursereview.list', compact('nav','breads'));
    }

    public function reviewAll(Request $request){
      $reviews = CourseReview::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
       if($review = $request->review) {
           $reviews->where('review', 'like', "%$review%");
       }
       if($course_id = $request->course_id) {
         $courses = Course::where('name', 'like', "%$course_id%")->pluck('id')->toArray();
        $reviews->whereIn('course_id', $courses);
       }
      if($review_by = $request->review_by) {
        $users = User::where('name', $review_by)->pluck('id')->toArray();
        $astrologers = Astrologer::where('fullname', $review_by)->pluck('id')->toArray();
        $by = array_merge($users,$astrologers);
        $reviews->whereIn('review_by', $by);
      }
       if($rating_from = $request->rating_from) {
           $reviews->where('rating', '>=', (int)$rating_from);
       }
      if($rating_to = $request->rating_to) {
          $reviews->where('rating', '<=', (int)$rating_to);
      }
       if(isset($request->status) && is_numeric($request->status)) {
          $reviews->where('status', $request->status);
       }
      if($updated_at_from = $request->updated_at_from) {
          $reviews->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }
      if($updated_at_to = $request->updated_at_to) {
          $reviews->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }
      $reviews = $reviews->paginate(10);
      $data = [
          'rows' => view('admin.coursereview.listrows',['results'=>$reviews])->render(),
          'pagination' => view('admin._partials.pagination',['results'=>$reviews])->render(),
      ];
      return response()->json($data, 200);
    }

    public function reviewForm(Request $request){
      $id = $request->id;
      $nav = 'courseReview';
      $review = CourseReview::find($id);
      return view('admin.coursereview.form', compact('review','nav'));
    }

    public function reviewFormSubmit(Request $request) {
      $id = $request->id;
     if(!$id && !adminCan('course-review', 'add')){
       return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
     }
     if($id && !adminCan('course-review', 'update')){
       return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
     }
      $this->validate($request, [
          'review' => 'required|string',
          'rating' => 'required|numeric|not_in:0',
      ],[
          'review' => 'Review is Required',
          'rating' => 'Rating is Required',
      ]);
      $review = CourseReview::find($id);
      if ($review) {
          $review->rating = $request->rating;
          $review->review = $request->review;
          $review->status = $request->status ? $request->status : 0;
          $review->save();
      }
      return response()->json(['message' => 'Review Saved Successfully', 'redirectto' => route('admin.course.review')], 200);
    }

    public function all(Request $request){
      $course = course::orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');
       if($name = $request->name) {
           $course->where('name', 'like', "%$name%");
       }
       if(isset($request->type) && is_numeric($request->type)) {
           $course->where('type', $request->type);
       }
      if($price_from = $request->price_from) {
          $course->where('price', '>=', (int)$price_from);
      }
     if($price_to = $request->price_to) {
         $course->where('price', '<=', (int)$price_to);
     }

     if(isset($request->status) && is_numeric($request->status)) {
        $course->where('status', $request->status);
     }
     if(isset($request->publish) && is_numeric($request->publish)) {
        $course->where('publish', $request->publish);
     }
      if($category = $request->course_category_id) {
        $c = CourseCategory::where('name', 'like', "%$category%")->pluck('id')->toArray();
        $course->whereIn('course_category_id', $c);
      }
      if($updated_at_from = $request->updated_at_from) {
          $course->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
      }
      if($updated_at_to = $request->updated_at_to) {
          $course->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
      }
       $course = $course->paginate(10);

          $data = [
              'rows' => view('admin.courses.listrows',['courses'=>$course])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$course])->render(),
          ];
          return response()->json($data, 200);
    }

    public function form(Request $request){
      $id = $request->id; // Course ID (for edit)
      $nav = 'course'; // Navigation identifier

      // Breadcrumbs for the view (Edit or Add mode)
      $breads = [
          ['name' => 'Courses', 'url' => route('admin.course')],
          ['name' => ($id ? 'Edit' : 'Add')]
      ];

      // Fetch course data if editing
      $course = $id ? Course::with('image')->find($id) : false;

      // Fetch media files related to courses
      $medias = Media::where('type', 'course')->orderBy('created_at', 'DESC')->get();

      // Fetch status options from Course model
      $_status = Course::statusArray();
      $hsnCodes = HsnCode::where('status', 1)->get();

      // Fetch all instructors
      // $instructor = Instructor::all();
      $instructor = Astrologer::where('type', 'Instructor')->get();


      // Fetch course categories and set the first option to 'select'
      $_courseCategory = CourseCategory::pluck('name', 'id')->toArray();
      $_courseCategory[0] = 'Select'; // Adding a 'Select' option to the top
      ksort($_courseCategory); // Sort categories to ensure the 'Select' option is on top

      // Return the view and pass all required data
      return view('admin.courses.form', compact(
          'nav', 'breads', 'course', 'medias', '_status', '_courseCategory', 'instructor', 'hsnCodes'
      ));
  }


    public function submit(Request $request) {
    $id = $request->id;
       if(!$id && !adminCan('course', 'add')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
       if($id && !adminCan('course', 'update')){
         return response()->json(['errors'=>['permission'=>['No Permission']]], 402);
       }
    $userid = Auth::guard('admin')->user()->id;
    $this->validate($request, [
    'name' 				=> 'required|max:255|unique:courses,name,'.$id.',id',
    'slug' 				=> 'required|max:255|unique:courses,slug,'.$id.',id',
    'type' 		=> 'required|numeric|not_in:0',
    'course_category_id'=> 'required|numeric|not_in:0',
    'short_description' => 'required',
      // 'instructor_id'   => 'required|numeric|not_in:0', //instructor
    'description' 		=> 'required',
    'image_id' 			=> 'required|numeric',
    'price' 			=> 'required|gt:special_price|numeric|not_in:0',
    'duration' 			=> 'required',
    ],[
    'name.required' => 'Name is required',
    'slug.required' => 'Slug is required',
    'type.required' => 'Type is required',
    'course_category_id.required' => 'Category is required',
    'short_description.required' => 'Short Description is required',
    'description.required' => 'Description is required',
    // 'instructor_id.required' => 'Instructor is required',
    'image_id.required' => 'Image is required',
    'price.required' => 'Price is required',
      'price.gt' => 'Price must be greate than sale price',
      'duration.required' => 'Duration (months) is required',
    ]);

    if(isset($request->special_price) && is_numeric($request->special_price)){
    $this->validate($request, [
    'special_from_date' 				=> 'required|date',
    'special_to_date' 		=> 'required|date|after:special_from_date',
    ],[
    'special_from_date.required' => 'Special Price From Date is required',
    'special_to_date.required' => 'Special Price To Date is required',
    ]);
    }

    if($request->special_from_date || $request->special_to_date){
    $this->validate($request, [
    'special_price' 				=> 'required',
    ],[
    'special_price.required' => 'Special Price is required',
    ]);
    }

    if($request->type == 1){
    $this->validate($request, [
    'start_date' 				=> 'required|date',
    'start_time' 		=> 'required',
    'end_time' 		=> 'required',
    'day' 		=> 'required',
    'total_class' 		=> 'required',
    ],[
    'start_date.required' => 'Start Date is required',
    'start_time.required' => 'Start Time is required',
    'end_time.required' => 'End Time is required',
    'day.required' => 'Day is required',
    'total_class.required' => 'Total Classes is required',
    ]);
    }

    if($request->type != 1){
    $this->validate($request, [
         'purchase_type' 		=> 'required|numeric|not_in:0',
    ],[
         'purchase_type.required' => 'Purchase Type is required',
    ]);
    }
    if($request->meet_link){
    $this->validate($request, [
         'meet_date' 		=> 'required',
         'meet_start_time' 		=> 'required',
         'meet_end_time' 		=> 'required',
         'meet_type' 		=> 'required',
         'meet_platform' 		=> 'required',
         // 'meet_status' 		=> 'required',
    ],
      [
         'meet_date.required' => 'Meet date is required',
         'meet_start_time.required' => 'Meet start time is required',
         'meet_end_time.required' => 'Meet end time is required',
         'meet_type.required' => 'Meet type is required',
         'meet_platform.required' => 'Meet platform is required',
         // 'meet_status.required' => 'Meet Status is required',
    ]);
    }

    if($request->apply_gst){
    $this->validate($request, [
         'hsn_id' 		=> 'required',
    ],[
         'hsn_id.required' => 'GST is required',
    ]);
    }

    if ($id) {
    $course = Course::find($id);
    $course->updated_by = $userid;
    } else {
    $course = new Course();
    $course->created_by = $userid;
    }

    $course->image_id    		= $request->image_id;
    $course->name        		= $request->name;
    $course->short_name  		= $request->short_name;
    $course->course_category_id = $request->course_category_id;
    $course->type 				= $request->type;
    $course->course_type			    = $request->instructor_id;
    $course->short_description  = $request->short_description;
    $course->description 		= $request->description;
    $course->duration 			= $request->duration ? $request->duration : 0;
    $course->access				= $request->access ? $request->access : 0;
    $course->price			    = $request->price;
    $course->special_price      = $request->special_price ? $request->special_price : 0.00;
    $course->status             = $request->status ? $request->status : 0;
    $course->ilearn 			= $request->ilearn;
    $course->youtube 			= $request->youtube;
    $course->meta_title 		= $request->meta_title;
    $course->meta_keywords 		= $request->meta_keywords;
    $course->meta_description 	= $request->meta_description ? $request->meta_description : '';
    $course->order 				= $request->order ? $request->order : 0;
    $course->rating 			= $request->rating ? $request->rating : 0;
      $course->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
    $course->special_to_date = $request->special_to_date ? date('Y-m-d', strtotime($request->special_to_date)) : null;
    $course->special_from_date = $request->special_from_date ? date('Y-m-d', strtotime($request->special_from_date)) : null;
    $course->purchase_type			    = $request->purchase_type ? $request->purchase_type : 1;
    $course->start_date			    = $request->start_date ?? '';
    $course->start_time			    = $request->start_time ?? '';
    $course->end_time			    = $request->end_time ?? '';
    $course->total_month			    = $request->total_month ?? 0;
    $course->total_class			    = $request->total_class ?? 0;
    $course->meet_date			    = $request->meet_date?date('Y-m-d', strtotime($request->meet_date)):'';
    $course->meet_start_time			    = $request->meet_start_time?date('H:i:s', strtotime($request->meet_start_time)):'';
    $course->meet_end_time			    = $request->meet_end_time?date('H:i:s', strtotime($request->meet_end_time)):'';
    $course->meet_link			    = $request->meet_link;
    $course->meet_platform			    = $request->meet_platform;
    $course->meet_type			    = $request->meet_type;
    $course->meet_status			    = $request->meet_status?:0;
    $course->publish			    = $request->publish?:0;
    $course->apply_gst			    = $request->apply_gst?:0;
    $course->price_type			    = $request->price_type?:0;
    $course->gst_percentage			    = $request->gst_percentage?:0;
    $course->hsn_id			    = $request->hsn_id?:0;
    if(!$request->brochure_file){
      if($course->brochure && File::exists(public_path($course->brochure))) {
        unlink(public_path($course->brochure));
        $course->brochure = '';
      }
    }
    if($request->hasFile('brochure')){
      $this->validate($request, [
        'brochure'=>'required|mimes:pdf'
      ]);
      if($course->brochure && File::exists(public_path($course->brochure))) {
        unlink(public_path($course->brochure));
      }
      $fileName = '';
      $filePath = public_path('course');
      if(!File::exists($filePath)) {
          File::makeDirectory($filePath, 0777, true);
      }
      $filePath = public_path('course/broucher');
      if(!File::exists($filePath)) {
          File::makeDirectory($filePath, 0777, true);
      }
      $filePath = public_path('course/broucher/'.$course->id);
      if(!File::exists($filePath)) {
          File::makeDirectory($filePath, 0777, true);
      }
      $time = time();
      $fileName = $time.'.'.$request->brochure->getClientOriginalExtension();
      $request->brochure->move($filePath, $fileName);
      $course->brochure = 'course/broucher/'.$course->id.'/'.$fileName;
    }
    $course->save();
    if($request->day && count($request->day)>1){
      CourseDay::where('course_id', $course->id)->delete();
      foreach($request->day as $dayKey => $dayItem){
        $courseDay = new CourseDay();
        $courseDay->course_id = $course->id;
        $courseDay->day = $dayItem;
        $courseDay->save();
      }
    }
    // dd(count($request->heading));

    if($request->heading && count($request->heading) > 1){
      Specification::where('item_id', $course->id)->where('item_type', 'course')->where('type', 'specification')->delete();
      foreach($request->heading as $key => $item){
        $specification = new Specification();
        $specification->item_id = $course->id;
        $specification->icon_id = $request->icon_id[$key];
        $specification->heading = $item;
        $specification->text = $request->text[$key] ? $request->text[$key] : '';
        $specification->item_type = 'course';
        $specification->type = 'specification';
        $specification->save();
      }
    }
    if($request->youtube){
      $returnArray = videoDetails($request->youtube);
      $previewVideo = PreviewVideo::where('item_id', $course->id)->first();
      if(!$previewVideo){
        $previewVideo = new PreviewVideo();
        $previewVideo->item_id = $course->id;
        $previewVideo->item_type = 'course';
      }
      if($returnArray['video_id'] && $returnArray['description']){
        $previewVideo->type = 'youtube';
        $previewVideo->video_id = $returnArray['video_id'];
        $previewVideo->title = $returnArray['title'];
        $previewVideo->description = $returnArray['description'];
        $previewVideo->image = $returnArray['image'];
        $previewVideo->save();
      }elseif(str_contains($request->youtube, 'vimeo.com/')){
        $video_id = explode('/', $request->youtube);
        $video_hash = '';
        if(count($video_id)==5){
          $video_id = $video_id[3];
          $video_hash = $video_id[4];
        }elseif(count($video_id)==4){
          $video_id = $video_id[3];
        }
        $returnArray = vimeoVideo($video_id);
        if($returnArray['video_hash'] && $returnArray['player_embed_url']){
          $previewVideo->type = 'vimeo';
          $previewVideo->title = $returnArray['title'];
          $previewVideo->description = $returnArray['description'];
          $previewVideo->image = 'vimeo';
          $previewVideo->video_id = $returnArray['video_id'];
          $previewVideo->video_hash = $returnArray['video_hash'];
          $previewVideo->player_embed_url = $returnArray['player_embed_url'];
          $previewVideo->save();
        }
      }
    }
    return response()->json(['message' => 'Course Saved Successfully', 'redirectto' => route('admin.course.edit', [$course->id])], 200);
    }
  function generateUniqueSlug($name, $courseId = null) {
      $slug = Str::slug($name);
      $originalSlug = $slug;
      $countInCourses = Course::where('slug', $slug)->where('id', '!=', $courseId)->count();
      $countInCourseCategories = CourseCategory::where('slug', $slug)->count();
      $suffix = 1;
      while ($countInCourses > 0 || $countInCourseCategories > 0) {
          $slug = $originalSlug . '-' . $suffix;
          $suffix++;

          $countInCourses = Course::where('slug', $slug)->where('id', '!=', $courseId)->count();
          $countInCourseCategories = CourseCategory::where('slug', $slug)->count();
      }
      return $slug;
  }


      public function delete(Request $request)
    {
      if(!adminCan('course', 'delete')) return dd('No Permission');
      if($course_id = $request->id) {
        $course = Course::find($course_id);
        $course->delete();
         return response()->json(['message'=>'Course Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function duplicate(Request $request){
      if(!adminCan('course', 'add')) return dd('No Permission');
      if($id = $request->id) {
        $course = Course::find($id);
        $newcourse = $course->replicate();
        $newcourse->slug = $course->slug.'-'.$id;
        $newcourse->name = $course->name.' Cloned';
        $newcourse->save();
        $courseModule = CourseModule::where('course_id', $id)->get();
        foreach($courseModule as $cm){
          $ncm = $cm->replicate();
          $ncm->course_id = $newcourse->id;
          $ncm->save();

          $courseModuleContent = CourseModuleContent::where('course_id', $id)->where('module_id', $cm->id)->get();
          foreach($courseModuleContent as $cmc){
              $ncmc = $cmc->replicate();
              $ncmc->course_id = $newcourse->id;
              $ncmc->module_id = $ncm->id;
              $ncmc->save();

              $courseModuleQuestion = CourseModuleQuestion::where('content_id', $cmc->id)->where('course_id', $id)->where('module_id', $cm->id)->get();
              foreach($courseModuleQuestion as $cmq){
                $ncmq = $cmq->replicate();
                $ncmq->course_id = $newcourse->id;
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
        $courseDays = CourseDay::where('course_id', $id)->get();
        foreach($courseDays as $cd){
          $ncd = $cd->replicate();
          $ncd->course_id = $newcourse->id;
          $ncd->save();
        }
        $courseBenifits = CourseBenifits::where('course_id', $id)->get();
        foreach($courseBenifits as $cb){
          $ncb = $cb->replicate();
          $ncb->course_id = $newcourse->id;
          $ncb->save();
        }

        $specification = Specification::where('item_id', $id)->where('item_type', 'course')->where('type', 'specification')->get();
        foreach($specification as $s){
          $ns = $s->replicate();
          $ns->item_id = $newcourse->id;
          $ns->save();
        }

         return response()->json(['message'=>'Course Cloned successfully!'], 200);
      }
      return response()->json(['message'=>'Error in cloning data'], 401);
    }
}
