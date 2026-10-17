<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
    public function imageTestimonail()
    {
    return $this->hasOne('App\Model\Media','id', 'image_testimonail_id');
    }
    public function pages(){
      return $this->hasMany('App\Model\OnPage', 'table_id', 'id')->where('table', 'testimonials');
    }
}
