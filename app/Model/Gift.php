<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;
    public function image(){
      return $this->hasOne('App\Model\Media','id', 'icon_id');
    }
}
