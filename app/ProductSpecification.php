<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function image()
    {
    return $this->hasOne('App\Model\Media','id', 'icon_id');
    }
}
