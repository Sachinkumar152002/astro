<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\{CoursePurchaseNotification};

class LmsCourse extends Model
{
    use HasFactory, Notifiable;

    public function sendCoursePurchaseNotification(){
        $this->notify(new CoursePurchaseNotification($this));
    }
    public function routeNotificationForMail(){
      return $this->user_email;
    }
    public function course(){
      return $this->hasOne('App\Model\Course', 'id', 'course_id');
    }
    public function orderItem(){
      return $this->hasOne('App\OrderItem', 'id', 'order_item_id');
    }

    public function watchedModules()
    {
      return $this->hasMany('App\Model\CourseExploreModule','user_id', 'user_id')->where('user_type', $this->user_type)->where('course_id', $this->course_id)->where('passed', 1);
    }

    public function getPercentageAttribute(){
        $moduleCount = $this->course->modules->count();
        $watchedModulesCount = $this->watchedModules->count();
        return $moduleCount ? (int) (($watchedModulesCount/$moduleCount)*100) : 0;
    }
}
