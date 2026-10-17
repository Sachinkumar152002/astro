<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VastuServicePackage extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function services(){
      return $this->hasMany('App\Model\VastuServicePackageName', 'package_id', 'id');
    }
}
