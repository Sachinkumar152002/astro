<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;
    public function servicename()
      {
          return $this->belongsTo('App\Model\Service', 'services', 'id');
      }
      public function image()
      {
    return $this->hasOne('App\Model\Media','id', 'image_id');
      }
}
