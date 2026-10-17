<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }

    public function page(){
        return $this->hasOne('App\Model\Cms', 'id', 'page_id');
    }

}
