<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModuleQuestion extends Model
{
    use SoftDeletes;
    public static function statusArray()
    {
    $status = [
    1 => 'Active',
            0 => 'Inactive',
    ];
    return $status;
    }

    public static function typeArray()
    {
        $status = [
            0 => 'Objective',
            1 => 'Subjective',
        ];
        return $status;
    }

    public function createdby()
    {
    return $this->hasOne('App\Admin','id', 'created_by');
    }

    public function updatedby()
    {
        return $this->hasOne('App\Admin','id', 'updated_by');
    }

    public function userAssignmentAnswer(){
      $user = 0;
      $type = '';
      if(auth()->guard('web')->check()){
        $user = auth()->guard('web')->user()->id;
        $type = 'user';
      }elseif(auth()->guard('astrologer')->check()){
        $user = auth()->guard('astrologer')->user()->id;
        $type = 'astrologer';
      }
      return $this->hasOne('App\Model\AssignmentAnswer','question_id', 'id')->where('user_id', $user)->where('user_type', $type)->where('show_in', 'assignment');
    }

    public function quizzAnswer(){
      $user = 0;
      $type = '';
      if(auth()->guard('web')->check()){
        $user = auth()->guard('web')->user()->id;
        $type = 'user';
      }elseif(auth()->guard('astrologer')->check()){
        $user = auth()->guard('astrologer')->user()->id;
        $type = 'astrologer';
      }
      return $this->hasOne('App\Model\AssignmentAnswer','question_id', 'id')->where('user_id', $user)->where('user_type', $type)->where('show_in', 'quizz');
    }


    public static function optionArray()
    {
        $option = [
            1 => 'A',
            2 => 'B',
            3 => 'C',
            4 => 'D',
        ];
        return $option;
    }
}
