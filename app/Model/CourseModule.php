<?php

namespace App\Model; // Ensure the namespace is correct

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModule extends Model
{
    use SoftDeletes;

    // Static array for status
    public static function statusArray()
    {
        return [
            1 => 'Active',
            0 => 'Inactive',
        ];
    }

    // Relationship to the created admin
    public function createdBy()
    {
        return $this->hasOne('App\Admin', 'id', 'created_by');
    }

    // Relationship to CoursePreview
    public function coursePreviews()
    {
        // Assuming course_module_id is the foreign key in CoursePreview table
        return $this->hasMany('App\CoursePreview', 'course_module_id', 'id');
    }

    // Relationship to the updated admin
    public function updatedBy()
    {
        return $this->hasOne('App\Admin', 'id', 'updated_by');
    }

    // Relationship to Course
    public function course()
    {
        return $this->hasOne('App\Model\Course', 'id', 'course_id');
    }

    // Relationship to CourseModuleFile
    public function files()
    {
        return $this->hasMany('App\Model\CourseModuleFile', 'module_id', 'id');
    }

    // You might want to keep it or remove it based on your requirement
    public function coursePreview()
    {
        return $this->hasMany('App\CoursePreview', 'course_module_id', 'id'); // This may need adjustment
    }

    // Relationship to CourseModuleQuestion
    public function questionList()
    {
        return $this->hasMany('App\Model\CourseModuleQuestion', 'module_id', 'id')->orderBy('order', 'asc');
    }

    // Relationship to CourseModuleQuestion
    public function lastUpdatedAssignmentQuestion()
    {
        return $this->hasOne('App\Model\CourseModuleQuestion', 'module_id', 'id')->where('show_in', 'assignment')->orderBy('updated_at', 'DESC');
    }

    // Relationship to CourseModuleQuestion
    public function lastUpdatedQuizzQuestion()
    {
        return $this->hasOne('App\Model\CourseModuleQuestion', 'module_id', 'id')->where('show_in', 'quizz')->orderBy('updated_at', 'DESC');
    }

    // Relationship to CourseModuleQuestion
    public function assignmentQuestions()
    {
        return $this->hasMany('App\Model\CourseModuleQuestion', 'module_id', 'id')->where('show_in', 'assignment')->orderBy('order', 'asc');
    }

    // Relationship to CourseModuleQuestion
    public function quizzQuestions()
    {
        return $this->hasMany('App\Model\CourseModuleQuestion', 'module_id', 'id')->where('show_in', 'quizz')->orderBy('order', 'asc');
    }

    // Relationship to CourseModuleQuestion
    public function content()
    {
        return $this->hasMany('App\CourseModuleContent', 'module_id', 'id')->where('status',1)->orderBy('order', 'ASC');
    }

    public function getAssignmentAnswerAttribute()
    {
      $user = 0;
      $type = '';
      if(auth()->guard('web')->check()){
        $user = auth()->guard('web')->user()->id;
        $type = 'user';
      }elseif(auth()->guard('astrologer')->check()){
        $user = auth()->guard('astrologer')->user()->id;
        $type = 'astrologer';
      }
      return AssignmentAnswer::where('user_id', $user)->where('user_type', $type)->where('module_id', $this->id)->where('show_in', 'assignment')->get();
      // return $this->hasMany('App\Model\AssignmentAnswer', 'module_id', 'id');
    }

    public function getQuizzAnswerAttribute()
    {
      $user = 0;
      $type = '';
      if(auth()->guard('web')->check()){
        $user = auth()->guard('web')->user()->id;
        $type = 'user';
      }elseif(auth()->guard('astrologer')->check()){
        $user = auth()->guard('astrologer')->user()->id;
        $type = 'astrologer';
      }
      return AssignmentAnswer::where('user_id', $user)->where('user_type', $type)->where('module_id', $this->id)->where('show_in', 'quizz')->get();
      // return $this->hasMany('App\Model\AssignmentAnswer', 'module_id', 'id');
    }

    public function hasPassed()
    {
      $user_id = 0;
      $user_type = '';
      if(auth()->guard('web')->check()){
        $user_id = auth()->guard('web')->user()->id;
        $user_type = 'user';
      }elseif(auth()->guard('astrologer')->check()){
        $user_id = auth()->guard('astrologer')->user()->id;
        $user_type = 'astrologer';
      }
      return $this->hasOne('App\Model\CourseExploreModule', 'module_id', 'id')->where('user_id', $user_id)->where('user_type', $user_type)->where('passed',1);
    }

}
