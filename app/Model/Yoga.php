<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Yoga extends Model
{
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
    public function bannerImage()
    {
    return $this->hasOne('App\Model\Media','id', 'banner_image_id');
    }
}
