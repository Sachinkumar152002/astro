<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    public function image(){
      return $this->hasOne('App\Model\Media','id','image_id');
    }

    public function parentCat(){
      return $this->hasOne('App\Model\ProductCategory','id','parent_id');
    }

    public function products(){
      return $this->hasMany('App\Product', 'category_id', 'id');
    }
    public function productAndCategoryIds(){
      return $this->hasMany('App\Model\ProductAndCategoryId', 'category_id', 'id');
    }
}
