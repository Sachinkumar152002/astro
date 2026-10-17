<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModuleContent extends Model
{
    use HasFactory;

    public function preview (){
      return $this->hasOne('App\CoursePreview', 'content_id', 'id');
    }

    public function videos (){
      return $this->hasOne('App\Model\CourseVideo', 'content_id', 'id');
    }

    public function files (){
      return $this->hasOne('App\Model\CourseModuleFile', 'content_id', 'id');
    }

    public function faqs (){
      return $this->hasMany('App\Model\CourseModuleQuestion', 'content_id', 'id');
    }
    public function assignmentQuestions(){
      return $this->hasMany('App\Model\CourseModuleQuestion', 'content_id', 'id')->where('show_in', 'assignment')->orderBy('order', 'asc');
    }
    public function quizzQuestions(){
      return $this->hasMany('App\Model\CourseModuleQuestion', 'content_id', 'id')->where('show_in', 'quizz')->orderBy('order', 'asc');
    }
}
