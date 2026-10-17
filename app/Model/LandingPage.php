<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    use HasFactory;

    public function icon()
    {
    return $this->hasOne('App\Model\Media','id', 'icon_id');
    }

}
