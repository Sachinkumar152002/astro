<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }

    public function mobileImage()
    {
    return $this->hasOne('App\Model\Media','id', 'mobile_image_id');
    }

    public function createdby()
    {
    return $this->hasOne('App\Admin','id', 'created_by');
    }

    public function updatedby()
    {
    return $this->hasOne('App\Admin','id', 'updated_by');
    }
}
