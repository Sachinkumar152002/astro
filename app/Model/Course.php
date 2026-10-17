<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
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

    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
    // public function instructor()
    // {
    //     return $this->belongsTo('App\Instructor', 'course_type', 'id');
    // }
    public function instructor()
    {
        return $this->belongsTo('App\Model\Astrologer', 'course_type', 'id');
        // return $this->hasOne('App\Model\Astrologer', 'id', 'course_type');
    }

    public function createdby()
    {
    return $this->hasOne('App\Admin','id', 'created_by');
    }

    public function updatedby()
    {
        return $this->hasOne('App\Admin','id', 'updated_by');
    }

    public function association()
    {
        return $this->hasOne('App\CourseCategory','id', 'course_category_id');
    }

    // public function course(){
    //   return $this->hasMany('App\OrderItem', 'course_id', 'id');
    // }

    public function category()
    {
        return $this->hasOne('App\CourseCategory','id', 'course_category_id');
    }

    public function modules()
    {
    return $this->hasMany('App\Model\CourseModule','course_id', 'id')->where('status',1)->orderBy('order','asc');
    }

    public function moduless()
    {
    return $this->hasMany('App\Model\CourseModule','course_id', 'id');
    }

    public function videoscount()
    {
      return $this->hasMany('App\Model\CourseVideo', 'course_id', 'id')->where('status', 1)->orderBy('created_at', 'asc');
    }

    public function getOfferPriceAttribute(){
        $offerPrice = $this->special_price;
        $today = strtotime(date('Y-m-d'));
        if($offerPrice) {
            if(!$this->special_from_date && !$this->special_to_date) {
                return $offerPrice;
            }elseif($this->special_from_date && !$this->special_to_date && strtotime($this->special_from_date) <= $today) {
                return $offerPrice;
            }elseif($this->special_to_date && !$this->special_from_date && strtotime($this->special_to_date) >= $today){
                return $offerPrice;
            }elseif($this->special_to_date && $this->special_from_date && strtotime($this->special_from_date) <= $today && strtotime($this->special_to_date) >= $today) {
                 return $offerPrice;
            }
        }
        return 0;
    }

    public function specifications()
    {
    return $this->hasMany('App\Specification','item_id', 'id')->where('item_type', 'course');
    }

    public function courseDays()
    {
    return $this->hasMany('App\CourseDay','course_id', 'id');
    }

    public function previewVideo()
    {
    return $this->hasOne('App\PreviewVideo','item_id', 'id');
    }
    public function reviews(){
      return $this->hasMany('App\CourseReview', 'course_id', 'id')->where('status', 1);
    }
    public function benifits(){
      return $this->hasMany('App\CourseBenifits', 'course_id', 'id')->where('status', 1);
    }
    public function benifitss(){
      return $this->hasMany('App\CourseBenifits', 'course_id', 'id');
    }

    public function faqs() {
      return $this->hasMany('App\Model\TemplateFaq','category_id','course_category_id')->where('type', 1);
    }

    public function topics() {
      return $this->hasMany('App\CourseModuleContent','course_id','id')->where('status', 1);
    }
    public function wishlist(){
      $user = 0;
      $type = '';
      if(auth()->guard('web')->check()){
        $user = auth()->guard('web')->user()->id;
        $type = 'user';
      }elseif(auth()->guard('astrologer')->check()){
        $user = auth()->guard('astrologer')->user()->id;
        $type = 'astrologer';
      }
      return $this->hasOne('App\Model\Wishlist','item_id','id')->where('type', 'course')->where('user_id', $user)->where('user_type', $type);
    }
    public function getPassedModulesAttribute(){
      return CourseExploreModule::where('course_id', $this->id)->get();
    }
    public function passedModules($user_id, $user_type){
      return CourseExploreModule::where('course_id', $this->id)->where('user_id', $user_id)->where('user_type', $user_type)->get();
    }

    public function orderItem(){
      $user = 0;
      $type = '';
      if(auth()->guard('web')->check()){
        $user = auth()->guard('web')->user()->id;
        $type = 'user';
      }elseif(auth()->guard('astrologer')->check()){
        $user = auth()->guard('astrologer')->user()->id;
        $type = 'astrologer';
      }
      return $this->hasMany('App\OrderItem','product_id','id')->where('user_id', $user)->where('user_type', $type)->where('type', 'course');
    }

    public static function lms($user_id = 0, $course_id = 0){

      // $user_id = 0;
      $user_type = 'user';
      // if(auth()->guard('web')->user()){
      //   $user_id = auth()->guard('web')->user()->id;
      // }
      // return $this->hasOne('App\Model\LmsCourse','course_id','id')->where('user_id', $user_id)->where('user_type', $user_type);
      return \App\Model\LmsCourse::where('course_id', $course_id)->where('user_id', $user_id)->where('user_type', $user_type)->first();
    }

    public function purchased(){
      $user_id = 0;
      $user_type = 'user';
      if(auth()->guard('web')->user()){
        $user_id = auth()->guard('web')->user()->id;
      }
      return $this->hasOne('App\Model\LmsCourse','course_id','id')->where('user_id', $user_id)->where('user_type', $user_type)->where('expire_date', '>=', date('Y-m-d'));
    }

    public function hnsCode(){
      return $this->hasOne('App\Model\HsnCode', 'id', 'hsn_id');
    }
}
