<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubPage extends Model
{
    public function bannerImage()
    {
    return $this->hasOne('App\Model\Media','id', 'banner_image_id');
    }
}
