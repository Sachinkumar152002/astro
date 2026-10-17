<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'icon_id');
    }
}
