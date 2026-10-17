<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePackage extends Model
{
    use HasFactory;
    public function services()
    {
    return $this->hasMany('App\Model\ModuleService','package_id', 'id');
    }
}
