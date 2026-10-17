<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Course;
class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function courseAll(Request $request){
    $courses = Course::select('slug', 'name','short_name','short_description','image_id', 'price','special_price','special_price','special_from_date', 'type', 'course_type')
    ->where('status',1);
    if($type = strtolower($request->type)){
      if($type=='live') $courses->where('type', 1);
      if($type=='recorded') $courses->where('type', 2);
      if($type=='online') $courses->where('type', 3);
    }
    $courses = $courses->orderBy('order', 'asc')->get();
    $courses = $courses->map(function($course){
        $course->img = $course->image ? $course->image->image(600) : no_image();
        $course->course_type = courseTypes($course->type);
        $course->short_description = strip_tags($course->short_description);
        $course->instructor_name = $course->instructor ? $course->instructor->name : '';
        unset($course->image);
        unset($course->type);
        unset($course->image_id);
        unset($course->instructor);
        $course->offer_price = $course->offerPrice;
        return $course;
    });
    // Return the courses data as JSON
       return response()->json([
           'success' => true,
           'data' => $courses,
           'message' => 'Courses retrieved successfully'
       ], 200);
  }

  public function courseDetail(Request $request){
    $course = Course::where('slug', $request->slug)->where('status',1)->first();
    if($course){
        $course->img = $course->image ? $course->image->image(600) : no_image();
        $course->course_type = courseTypes($course->type);
        $course->category_name = $course->category ? $course->category->name : 'na';
        $course->offer_price = $course->offerPrice;
        $course->instructor_name = $course->instructor ? $course->instructor->name : '';
        unset($course->id ,$course->image ,$course->image_id ,$course->certified_by ,$course->type ,$course->meta_keywords ,$course->meta_description ,$course->status ,$course->created_at ,$course->updated_at ,$course->order ,$course->created_by ,$course->updated_by ,$course->deleted_at ,$course->course_category_id ,$course->category, $course->instructor);

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Course retrieved successfully'
        ], 200);
    }
    return response()->json([
        'error' => true,
        'data' => [],
        'message' => 'No Course Found'
    ], 403);
  }

  public function courseDetailModules(Request $request){
    $course = Course::where('slug', $request->slug)->where('status',1)->first();
    if($course){
        $modules = $course->modules->map(function($module){
          $previewFiles = [];
          foreach($module->coursePreviews as $preview){
            $previewFiles[] = ['video_id'=>$preview->video_id, 'source'=>'youtube'];
          }
          $data = [
            'name' => $module->name,
            'total_time' => $module->total_time,
            'details' => $module->details,
            'previewfiles' => $previewFiles,
          ];
          return $data;
        });

        return response()->json([
            'success' => true,
            'data' => $modules,
            'message' => 'Course Module retrieved successfully'
        ], 200);
    }
    return response()->json([
        'error' => true,
        'data' => [],
        'message' => 'No Course Found'
    ], 403);
  }
}
