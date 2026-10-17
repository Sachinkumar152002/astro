<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Course;
use App\Model\CourseModule;
use App\Model\CourseModuleQuestion;
use App\Model\Association;
use App\{CourseModuleContent};
use Auth;

class CourseModuleQuestionController extends Controller
{
     public function index(Request $request)
    {

        $course_id = $request->course_id;
        $course = Course::find($course_id);
        if(!$course) return redirect()->route('admin.course');

        $module_id = $request->module_id;
        $module = CourseModule::find($module_id);
        if(!$module) return redirect()->route('admin.course.module', [$course_id]);

        $content_id = $request->content_id;
        $content = CourseModuleContent::find($content_id);
        if(!$content) return redirect()->route('admin.course.module.content', [$course_id,$module_id]);
        $faq_type = $request->faq_type;

    $nav = 'course';
        // $breads = [
        // 	['name'=>'Courses', 'url'=> route('admin.course')],
        // 	['name'=>'Modules', 'url'=> route('admin.course.module', [$course_id]) ],
        // 	['name'=>'Content', 'url'=> route('admin.course.module.content', [$course_id, $module_id]) ],
        //   ['name'=>'Questions']
        // ];
        $breads = [
    ['name'=>'Modules', 'url'=> ''],
    ['name'=>'Content', 'url'=> route('admin.course.module.content', [$course_id, $module_id]) ],
          ['name'=>ucfirst($faq_type)]
        ];

        return view('admin.courses.modules.questions.list', compact('nav','breads', 'course_id', 'course', 'module_id', 'content_id', 'module', 'content', 'faq_type') );
    }

    public function all(Request $request)
    {
     $course_id = $request->course_id;
     $module_id = $request->module_id;
     $content_id = $request->content_id;
     $faq_type = $request->faq_type;
       $questions = CourseModuleQuestion::where('content_id', $content_id)->where('show_in', $faq_type)->orderBy('type', 'asc')->orderBy('order', 'asc');
       // $questions = CourseModuleQuestion::where('content_id', $content_id)->orderBy($request->sort_by?:'updated_at',$request->sort_dir?:'desc');

       if($question = $request->question) {
           $questions->where('question', 'like', "%$question%");
       }
       // if($show_in = $request->show_in) {
       //     $questions->where('show_in', 'like', "%$show_in%");
       // }

       // if(isset($request->status) && is_numeric($request->status)) {
       //    $questions->where('status', $request->status);
       // }
       if($updated_at_from = $request->updated_at_from) {
           $questions->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') >= ? " , $updated_at_from);
       }
       if($updated_at_to = $request->updated_at_to) {
           $questions->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') <= ? " , $updated_at_to);
       }
       $questions = $questions->paginate(10);

          $data = [
              'rows' => view('admin.courses.modules.questions.listrows',['questions'=>$questions, 'course_id'=>$course_id, 'module_id'=>$module_id, 'content_id'=>$content_id, 'faq_type'=>$faq_type])->render(),
              'pagination' => view('admin._partials.pagination',['results'=>$questions])->render(),
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

        $content_id = $request->content_id;
        $content = CourseModuleContent::find($content_id);
        if(!$content) return redirect()->route('admin.course.module.content', [$course_id, $module_id]);
        $faq_type = $request->faq_type;

        $id = $request->id;
        $nav = 'course';
        // $breads = [
        // 	['name'=>'Courses', 'url'=> route('admin.course')],
        // 	['name'=>'Modules', 'url'=> route('admin.course.module', [$course_id])],
        //     ['name'=>'Questions']
        // ];
        $breads = [
    ['name'=>'Modules', 'url'=> ''],
    ['name'=>'Content', 'url'=> route('admin.course.module.content', [$course_id, $module_id]) ],
          ['name'=>ucfirst($faq_type), 'url'=>route('admin.course.module.content.question', [$course->id, $module_id, $content_id, $faq_type])]
        ];

        $course_module_question = false;
        if($id) { $course_module_question = CourseModuleQuestion::find($id); }
        $_status = CourseModuleQuestion::statusArray();
        $_types = CourseModuleQuestion::typeArray();
        $_options = CourseModuleQuestion::optionArray();

        return view('admin.courses.modules.questions.form', compact('nav', 'breads', 'course_module_question', '_status',  'course_id', 'course', 'module_id', 'content_id', 'module', '_options', '_types', 'faq_type'));
    }

    public function submit(Request $request) {
    $this->validate($request, [
        'type' 		=> 'required|numeric',
        // 'show_in' 		=> 'required',
    'question' 				=> 'required',
    'details' 		=> 'required',
    ],[
    'type.required' => 'Type is required',
    // 'show_in.required' => 'Show In is required',
    'question.required' => 'Question is required',
    'details.required' => 'Detail Type is required',
    ]);
        if($request->type==0) {
    $this->validate($request, [
                'option_a' 		=> 'required',
    'option_b' 				=> 'required',
    'option_c' 		=> 'required',
    'option_d' 		=> 'required',
    'answer' 		=> 'required|numeric:not_in:0',
    ],[
    'option_a.required' => 'Option A is required',
    'option_b.required' => 'Option B is required',
    'option_c.required' => 'Option C is required',
    'option_d.required' => 'Option D is required',
    'answer.required' => 'Answer is required',
    ]);
        }elseif($request->type==1) {
          $this->validate($request, [
            'keywords' 		=> 'required',
          ],[
            'keywords.required' => 'Keywords is required',
          ]);
        }

    $id = $request->id;
    $course_id = $request->course_id;
    $module_id = $request->module_id;
    $content_id = $request->content_id;
    $faq_type = $request->faq_type;
    $userid = Auth::guard('admin')->user()->id;
    if($id){
    $courseModuleQuestion = CourseModuleQuestion::find($id);
    $courseModuleQuestion->updated_by 	= $userid;
    }else {
    $courseModuleQuestion = new CourseModuleQuestion();
    $courseModuleQuestion->created_by 	= $userid;
    }
    $courseModuleQuestion->course_id = $request->course_id;
    $courseModuleQuestion->module_id = $request->module_id;
    $courseModuleQuestion->content_id = $request->content_id;
    $courseModuleQuestion->question  = $request->question;
    $courseModuleQuestion->details 	 = $request->details ? $request->details : '';
    $courseModuleQuestion->option_a  = $request->option_a;
    $courseModuleQuestion->option_b  = $request->option_b;
    $courseModuleQuestion->option_c  = $request->option_c;
    $courseModuleQuestion->option_d  = $request->option_d;
    $courseModuleQuestion->keywords  = $request->keywords;
    $courseModuleQuestion->type 		 = $request->type;
    // $courseModuleQuestion->show_in 		 = $request->show_in;
    $courseModuleQuestion->show_in 		 = $request->faq_type;
    $courseModuleQuestion->answer 	 = $request->answer ? $request->answer : 1;
    $courseModuleQuestion->time 		 = $request->time ? $request->time : 1;
    $courseModuleQuestion->order 		 = $request->order ? $request->order : 0;
    $courseModuleQuestion->status 	 = $request->status ? $request->status : 0;

    $courseModuleQuestion->save();

    return response()->json(['message'=>'Course Module Question Saved Successfully', 'redirectto'=>route('admin.course.module.content.question', [$course_id, $module_id, $content_id, $faq_type, $courseModuleQuestion->id])], 200);
    return $request;
    }

      public function delete(Request $request)
    {
      if($question_id = $request->id) {
        $CourseModuleQuestion = CourseModuleQuestion::find($question_id);
        $CourseModuleQuestion->delete();
         return response()->json(['message'=>'Course Module Question Deleted successfully!'], 200);
      }
      return response()->json(['message'=>'Error in deleting data'], 401);
    }

    public function duplicate(Request $request){
      if($id = $request->course_id) {

          $module_id = $request->module_id;
          $qid = $request->id;
          $content_id = $request->content_id;

          // $courseModuleContent = CourseModuleContent::where('id', $content_id)->where('course_id', $id)->where('module_id', $module_id)->get();
          // foreach($courseModuleContent as $cmc){
              $cmq = CourseModuleQuestion::find($qid);
          //     foreach($courseModuleQuestion as $cmq){
                $ncmq = $cmq->replicate();
                $ncmq->course_id = $id;
                $ncmq->module_id = $module_id;
                $ncmq->content_id = $content_id;
                $ncmq->question = $cmq->question.' - Cloned';
                $ncmq->save();
          //     }
          // }
         return response()->json(['message'=>'Course Module Content Question Cloned successfully!'], 200);
      }
      return response()->json(['message'=>'Error in cloning data'], 401);
    }
}
