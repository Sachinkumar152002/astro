<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;

    protected $table = 'landings';

     public function icon()
    {
    return $this->hasOne('App\Model\Media','id', 'icon_id');
    }
}
