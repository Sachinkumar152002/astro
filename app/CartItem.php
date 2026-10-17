<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    public function product()
   {
       return $this->hasOne('App\Product', 'id', 'item_id');
   }
   public function course()
   {
       return $this->hasOne('App\Model\Course', 'id', 'item_id');
   }

   public function cartProduct() {
     return $this->hasMany('App\Product','id', 'product_id');
   }

   public function user()
   {
       return $this->hasOne('App\User', 'id', 'user_id');
   }


}
