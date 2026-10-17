<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    public function products(){
      return $this->hasMany('App\Model\ProductTag', 'tag_id', 'id');
    }
}
