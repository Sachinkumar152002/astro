<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Remedie extends Model
{
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }

    public function childPages(){
        return $this->hasMany('App\Model\Remedie', 'type', 'slug');
    }

    public function bannerImage()
    {
    return $this->hasOne('App\Model\Media','id', 'banner_image_id');
    }
}
