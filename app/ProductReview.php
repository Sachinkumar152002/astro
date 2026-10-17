<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;
    public function user(){
      return $this->hasOne('App\User', 'id', 'review_by');
    }
    public function astrologer(){
      return $this->hasOne('App\Model\Astrologer', 'id', 'review_by');
    }
    public function previews(){
      return $this->hasOne('App\Product', 'id', 'product_id');
    }
    public function products(){
      return $this->hasMany('App\Product', 'id', 'product_id')->where('status', 1);
    }
}
