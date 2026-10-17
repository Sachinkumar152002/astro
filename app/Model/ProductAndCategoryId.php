<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAndCategoryId extends Model
{
    use HasFactory;
    public function category(){
    return $this->hasOne('App\Model\ProductCategory','id', 'category_id');
    }
}
