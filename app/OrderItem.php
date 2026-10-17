<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
       protected $guarded = [];

       public function course() {
          return $this->hasOne('App\Model\Course', 'id', 'product_id');
      }
       public function product()
   {
       return $this->hasOne('App\Product', 'id', 'product_id');
   }

   // Relationship to the Order model
   public function order()
   {
       return $this->belongsTo('App\Order', 'order_id', 'id');
   }

   public function products() {
     return $this->hasMany('App\Product', 'id', 'product_id');
   }

   public function image()
   {
     return $this->hasOne('App\Model\Media','id', 'image_id');
   }


}
