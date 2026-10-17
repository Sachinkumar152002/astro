<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PAttribute extends Model
{
    use HasFactory;
    public function attribute(){
      return $this->hasOne('App\Model\Attribute', 'id', 'attribute_id');
    }

    public function pAttributeValues(){
      return $this->hasMany('App\Model\PAttributeValue', 'p_attribute_id', 'id')->groupBy('value');
    }

}
