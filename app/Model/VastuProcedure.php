<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VastuProcedure extends Model
{
    use HasFactory;
    public $table = 'vastu_procedures';
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
}
