<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    use HasFactory;
    public function liveCourses(){
      return $this->hasMany('App\Model\Course', 'course_category_id', 'id')->where('type', 1);
    }
    public function onlineCourses(){
      return $this->hasMany('App\Model\Course', 'course_category_id', 'id')->where('type', 2);
    }

    public function parent(){
      return $this->hasOne('App\CourseCategory','id','parent_id');
    }

    public function image(){
      return $this->hasOne('App\Model\Media','id','image_id');
    }

    public function iconImage() {
      return $this->hasOne('App\Model\Media','id','icon_image_id');
    }

    public function courses(){
      return $this->hasMany('App\Model\Course', 'course_category_id', 'id');
    }
}
