<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    public function tag(){
    return $this->hasOne('App\Model\Tag','id', 'tag_id');
    }
    public function product(){
    return $this->hasOne('App\Product','id', 'product_id')->where('status', 1);
    }
}
