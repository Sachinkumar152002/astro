<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    public function product(){
      return $this->hasOne('App\Product', 'id', 'item_id');
    }
    public function course(){
      return $this->hasOne('App\Model\Course', 'id', 'item_id');
    }
}
