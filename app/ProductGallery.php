<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory;

    public function image()
    {
      return $this->hasOne('App\Model\Media','id', 'image_id');
    }
}
