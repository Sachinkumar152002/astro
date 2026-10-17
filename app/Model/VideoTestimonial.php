<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTestimonial extends Model
{
    use HasFactory;
    public function pages(){
      return $this->hasMany('App\Model\OnPage', 'table_id', 'id')->where('table', 'video_testimonials');
    }
}
