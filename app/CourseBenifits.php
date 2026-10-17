<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseBenifits extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'image_id');
    }
}
