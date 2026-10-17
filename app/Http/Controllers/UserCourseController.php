<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserCourse;

class UserCourseController extends Controller
{
  public function submit(Request $request)
{
    // Access user_id and course_id from the request
    $user_id = $request->input('user_id');
    $course_id = $request->input('course_id');

    // Create a new UserCourse record
    $course = new UserCourse();
    $course->user_id = $user_id; // Corrected assignment operator
    $course->course_id = $course_id; // Fixed typo in variable name
    $course->save(); // Save the record to the database

    // Return a JSON response indicating success
    return response()->json(['message' => 'Enrollment successful']);
}

}
