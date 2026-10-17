<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    use HasFactory;
    public function user(){
      return $this->hasOne('App\User', 'id', 'review_by');
    }
    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'review_by');
    }

    public function courseReviews(){
      return $this->hasOne('App\Model\Course', 'id', 'course_id');
    }
}
